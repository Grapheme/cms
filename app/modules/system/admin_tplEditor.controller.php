<?php

class AdminTplEditorController extends BaseController {

    public static $name = 'tpl_editor';
    public static $group = 'system';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        $name = self::$name;
        $group = self::$group;
        Route::group(array('before' => 'auth', 'prefix' => 'admin'), function() use ($class, $name, $group) {
            Route::get($group . '/' . $name . '/edit/{mod}', $class . '@getEdit');
            Route::get($group . '/' . $name . '/save/{mod}', $class . '@postSave');
            Route::controller($group . '/' . $name, $class);
        });
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }

    /****************************************************************************/

	public function __construct(){
		

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'tpl' => static::returnTpl('admin/tpl_editor'),
            'gtpl' => static::returnTpl(),

            'class' => __CLASS__,
        );
        View::share('module', $this->module);
	}

    public function getIndex() {

        $modules = Config::get('mod_info');
        #Helper::dd($modules);

        $templates = ModTemplates::get();
        #Helper::dd($templates);

        return View::make($this->module['tpl'].'index', compact('modules', 'templates'))->render();
    }

    public function getEdit($mod_name) {

        #Helper::d($mod_name);
        #Helper::dd(Input::all());

        $file = Input::get('tpl');
        $mod = Config::get('mod_info.'.$mod_name);
        $full_file = app_path('modules/'.$mod_name.'/views/'.$file.'.blade.php');

        #Helper::d($module);
        #Helper::dd($full_file);

        return View::make($this->module['tpl'].'edit', compact('mod_name', 'file', 'mod', 'full_file'));
    }

    public function postSave($mod_name) {

        #Helper::dd(Input::all());

        $json_request = array('status' => FALSE, 'responseText' => '');

        $file = Input::get('file');
        $tpl = Input::get('tpl');
        $full_file = app_path('modules/'.$mod_name.'/views/'.$file.'.blade.php');
        $result = @file_put_contents($full_file, $tpl);

        $json_request['status'] = true;
        return Response::json($json_request, 200);

    }

}
