<?php
$root = '/duan1';
if(!isset($_SESSION['currentUser']) && isset($_COOKIE['user_lg'])){
    $_SESSION['currentUser'] = json_decode(base64_decode($_COOKIE['user_lg']));
}

if(!isset($_SESSION['cart'])){$_SESSION['cart'] = [];}
// $_SESSION['cart'] = [];
// unset($_SESSION['currentUser']);

function get_view($path, $method = 'GET'){
    if($method === 'GET') {
        include('./views/partials/header.php');
        echo('<main>');
    }
    include($path);
    if($method === 'GET') { 
        include('./views/partials/footer.php');
        echo('</main>');
    }
}

function with_login($path,$url, $noheader = false, $require_admin = false){
    if(isset($_SESSION['currentUser'])) {
            if(!$noheader && !$require_admin){
                get_view($path);
            }elseif(!$noheader && $require_admin){
                if($_SESSION['currentUser']->role == 1){
                    $sidebar = file_get_contents('./views/partials/admin/sidebar.php');
                    $page = $path;
                    include('./views/admin/layout.php');
                }else{
                    header('location: '.get_home_url());
                }
            }elseif($noheader && !$require_admin){
                include($path);
            }else{
                if($_SESSION['currentUser']->role == 1){
                    include($path);
                }else{
                    header('location: '.get_home_url());
                }
            }
    }else{
        header('location: '.get_home_url().'/login?url='.get_current_url());
    }
}

function get_home_url(){
    global $root;
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$root";
}

function get_current_url(){
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

?>