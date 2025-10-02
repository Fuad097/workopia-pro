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


function loadView($name){
   $viewPath = basePath("views/{$name}.view.php");



   if(file_exists($viewPath)){
    require $viewPath;
   }else{
    echo "View {$name} not found";
   }



}
/**
 * @param string $name
 * @return void
 */


function loadPartial($name){
    $viewPart =  basePath("views/partials/{$name}.php");

    
   if(file_exists($viewPart)){
    require $viewPart;
   }else{
    echo "View {$name} not found";
   }
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


?>

