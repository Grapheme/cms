<?php

class PublicSarykumController extends BaseController {

    public static $name = 'sarykum_public';
    public static $group = 'sarykum';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        Route::post('/ajax/reserve-room', array('as' => 'ajax-reserve-room', 'uses' => __CLASS__.'@postAjaxReserveRoom'));

        Route::group(array('before' => 'i18n_url', 'prefix' => Config::get('app.locale')), function() {
            Route::get('/room/{slug}', array('as' => 'room', 'uses' => __CLASS__.'@getShowRoom'));
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {

    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }

    ## Menu elements of the module
    public static function returnMenu() {
        /*
        return array(
            array(
            	'title' => 'Галереи',
                'link' => self::$group,
                'class' => 'fa-picture-o',
                'permit' => 'view',
            ),
        );
        */
    }

    /****************************************************************************/
    
	public function __construct(){
        ##
	}

    public function postAjaxReserveRoom() {

        if(!Request::ajax())
            App::abort(404);

        $room_type = Input::get('room_type');

        if (!strpos($room_type, '.'))
            App::abort(404);

        #Helper::dd($room_type);

        list($room_id, $price_type) = explode('.', $room_type);

        $room = DicVal::find($room_id);

        if (!is_object($room)) {
            App::abort(404);
        }

        $json_request = array('status' => TRUE, 'responseText' => '');

        ## Send confirmation to user - with password
        $data = array(
            'room'       => $room,

            'room_type'  => Input::get('room_type'),
            'price_type' => $price_type,
            'date_start' => Input::get('date_start'),
            'date_stop'  => Input::get('date_stop'),
            'name'       => Input::get('name'),
            'contact'    => Input::get('contact'),
        );
        Mail::send('emails.reserve_success', $data, function ($message) use ($data) {
            $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
            $message->subject('Бронирование номера');
            $message->to(Config::get('mail.feedback.address'));
        });
        #Helper::dd($result);
        return Response::json($json_request, 200);
    }

    public function getShowRoom($slug) {

        $room = Dic::valueBySlugs('room_type', $slug, true);

        #Helper::tad($room);

        if (!is_object($room) || !$room->id) {
            App::abort(404);
        }

        return View::make(Helper::layout('room'), compact('room'));
    }


}


