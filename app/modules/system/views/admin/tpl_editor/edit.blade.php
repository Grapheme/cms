<?
$tpl_content = @file_get_contents($full_file);
?>
@extends(Helper::acclayout())


@section('style')
<style>
#tpl_content {
    width: 100%;
}
</style>
@stop


@section('content')

    <main class="content">

    @include($module['tpl'].'menu')

        @if (0)
        <div class="alert alert-warning fade in">
            <i class="fa-fw fa fa-warning"></i>
            <strong>Внимание!</strong> Необходимо выставить права на запись всем файлам и директориям внутри папки /lang.<br/>
            Для этого подключитесь к серверу по SSH и из корня приложения выполните команду: chmod -R 777 app/lang/<br/>
            Также проверьте, чтобы существовали все директории с языковыми версиями, которые указаны в конфигурации.
        </div>
        @else

        <div class="row margin-top-10">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                {{ Form::open(array('url' => URL::action($module['class'].'@postSave', array('mod' => $mod_name)), 'class' => 'smart-form2', 'id' => 'tpl-form', 'role' => 'form', 'method' => 'POST')) }}

                {{ Form::hidden('file', $file) }}

                <fieldset class="padding-top-10">
                    <section>
                        <label class="textarea">
                            {{ Form::textarea('tpl', $tpl_content, array('id' => 'tpl_content')) }}
                        </label>
                    </section>
                </fieldset>


                <fieldset class="padding-top-10">
                    <button class="btn btn-primary btn-lg submit">
                        <i class="fa fa-save"></i>
                        Сохранить
                    </button>
                </fieldset>

                {{ Form::close() }}

            </div>
        </div>
        @endif

    </main>

@stop


@section('scripts')

    {{ HTML::style('admin/js/codemirror/lib/codemirror.css') }}

    {{ HTML::script('admin/js/codemirror/lib/codemirror.js') }}

    {{ HTML::script('admin/js/codemirror/addon/edit/matchbrackets.js') }}
    {{ HTML::script('admin/js/codemirror/mode/htmlmixed/htmlmixed.js') }}
    {{ HTML::script('admin/js/codemirror/mode/xml/xml.js') }}
    {{ HTML::script('admin/js/codemirror/mode/clike/clike.js') }}
    {{ HTML::script('admin/js/codemirror/mode/php/php.js') }}

    <!-- Create a simple CodeMirror instance -->
    <script>
        var myTextarea = document.getElementById("tpl_content");
        var editor = CodeMirror.fromTextArea(myTextarea, {
            lineNumbers: true,
            matchBrackets: true,
            //mode: "text/html",
            mode: "application/x-httpd-php",
            indentUnit: 4,
            indentWithTabs: true,
            lineWrapping: true
        });
    </script>


    {{ HTML::script("js/vendor/jquery-form.min.js") }}

    <script>
        $(document).on('submit', '#tpl-form', function(e, selector, data) {

            e.preventDefault();
            var form = $(this);
            var options = { target: null, type: $(form).attr('method'), dataType: 'json' };

            options.beforeSubmit = function(formData, jqForm, options){
                $(form).find('button.submit').addClass('loading');
            }

            options.success = function(response, status, xhr, jqForm){
                //console.log(response);
                //$('.success').hide().removeClass('hidden').slideDown();
                //$(form).slideUp();

                if (response.status) {
                    showMessage.constructor('Сохранение', 'Успешно сохранено');
                    showMessage.smallSuccess();

                } else {
                    showMessage.constructor('Ошибка при сохранении', response.responseText);
                    showMessage.smallError();
                }

            }

            options.error = function(xhr, textStatus, errorThrown){
                console.log(xhr);
            }

            options.complete = function(data, textStatus, jqXHR){
                $(form).find('button.submit').removeClass('loading');
            }

            $(form).ajaxSubmit(options);

            return false;
        });
    </script>
@stop

