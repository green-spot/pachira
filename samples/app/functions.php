<?php

namespace App;

function get_login_user(){
  return el($_GET, "user");
}
