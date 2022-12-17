PHP Web Framework inspired by Sinatra.

## セットアップ
```
$ cd /path/to/document-root
$ composer require green-spot/pachira
$ cp -r vendor/green-spot/pachira/samples/. ./
```

## ドキュメント

### ルーティング

```php
get("/", function(){
  echo "Hello Pachira";
});

post("/", function(){
  some_side_effects();
});

request("/", function(){
  switch($_SERVER["REQUEST_METHOD"]){
    case "GET": echo "get"; break;
    case "POST": echo "post"; break;
    case "HEAD": echo "head"; break;
  }
});
```

動的ルーティングしたい場合は、**正規表現**で記述します。(頭に`^`、末尾に`$`が自動挿入されます。)

```php
get("/article/(.*?)/(.*)", function($category, $id){
  $article = get_article($id);
  echo $article->title;
});
```

#### pass()

pass()を使えば、次にマッチするルーティングに回すことができます。

```php
get("/admin/.*", function(){
  if(!logged_in()) redirect("/login");
  pass();
});

get("/admin/", function(){
  echo "Hello Pachira";
});
```

### ミドルウェア

```php
middleware("layout", function($run){
  view("layout", [
    "content" => capture($run)
  ]);
});

middleware("login_required", function($run){
  if(!logged_in()) redirect("/login");
  $run();
});
```

```php
get("/", function(){
  echo "Hello Pachira";
})->use("layout");

get("/admin", function(){
  echo "members only";
})->use(["login_required", "layout"]);
```

### グループ

group()を使うと、URLの階層化とミドルウェアの一括指定ができます。

```php
group("/member", ["login_required"], function($members_page){
  $members_page->get("/profile", function(){
    echo "profile page (/member/profile)";
  });
  
  $members_page->get("/articles/(.*)", function($id){
    echo "your article (/member/articles/{$id})";
  });
});
```

### ビュー

`$option["view"]["directory"]`を渡すと、ビューが使えるようになります。

```php
Pachira::run([
  "view" => [
    "directory" => __DIR__ . "/views/"
  ],
]);
```

```php
get("/", function(){
  view("top");
});

get("/article/(.*)", function($id){
  view("article", ["article" => get_article($id)]);
});
```

views/article.php
```html
<article>
  <h1><?php echo $article->title; ?></h1>
  <div class="body">
    <?php echo $article->content; ?>
  </body>
</article>
```

プラグインを使うと、Twigにも対応できます。<br>
https://github.com/green-spot/pachira-twig

```
$ composer require green-spot/pachira-twig
```

```php
get("/", function(){
  twig("top.html", ["key" => "val"]);
});
```

### プラグイン

プラグインを使うと、Pachiraを拡張することができます。

```php
Pachira::addPlugin("my-plugin", function($options){
  // 関数を追加
  function my_function(){
    echo "Hello";
  }
});
```

Pachira実行時にオプションを渡すことで、プラグインを実行できます。

```php
get("/", function(){
  my_function();
});

Pachira::run([
  "my-plugin" => []
]);
```

プラグイン内でオブジェクトを保持したい場合は、グローバルオブジェクトの`Pachira::$store`を使います。

```php
Pachira::addPlugin("my-plugin", function($options){
  Pachira::$store["my-plugin"] = $options["object"];
  
  function hello(){
    echo Pachira::$store["my-plugin"]->hello();
  }
});

Pachira::run([
  "my-plugin" => ["object" => new MyObject()]
]);
```
