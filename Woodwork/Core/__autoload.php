<?php

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();
spl_autoload_register(function($className){
	set_include_path('./Woodwork/Controllers');
	spl_autoload($className);
});
spl_autoload_register(function($className){
	set_include_path('./Woodwork/Models');
	spl_autoload($className);
});
spl_autoload_register(function($className){
	set_include_path('./Woodwork/Helpers');
	spl_autoload($className);
});