<?php   

/**
 * @param string $path
 * @return string
 */

function basePath($path=""){
   return __DIR__ . "/" . $path;
}



/**
 * @param string $name
 * @return void
 */


function loadView($name, $data=[]){
   $viewPath = basePath("App/views/{$name}.view.php");



   if(file_exists($viewPath)){
    extract($data);
    require $viewPath;
   }else{
    echo "View {$name} not found";
   }



}
/**
 * @param string $name
 * @return void
 */


function loadPartial($name,$data = []){
    $viewPart =  basePath("App/views/partials/{$name}.php");

    
   if(file_exists($viewPart)){
    extract($data);
    require $viewPart;
   }else{
    echo "View {$name} not found";
   }
}

function formatsalary($salary){
    return '$' . number_format(floatval($salary));
}


/**
 * @param  mixed $value
 * @return void
 */

function inspect($value){

    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}
/**
 * @param  mixed $value
 * @return void
 */

function inspectDie($value){

    echo "<pre>";
    die(var_dump($value));
    echo "</pre>";
}


/**
 * Sanitize data
 * @param string $value
 * @return string
 */
function sanitize($value){
    return filter_var($value,FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect page
 * @param string $url
 * @return void
 */

function redirect($url){
    header("Location:{$url}");
    exit;
}

?>

