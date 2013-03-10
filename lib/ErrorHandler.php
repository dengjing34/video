<?php
//dengjing34@vip.qq.com
class ErrorHandler {
    public static function show_404 ($heading = null ,$message = null) {
        $view = new View('error/error_404');
        $view->heading = is_null($heading) ? '404 error' : $heading;
        $view->message = is_null($message) ? 'page not found' : $message;
        $view->render(true);
        exit;
    }
}
?>
