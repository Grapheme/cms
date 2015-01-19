
    <!-- Don't forget add to parent page to scripts section JS code for correct functionality, some like this: -->
    <!-- loadScript("/js/modules/gallery.js"); -->


	<div class="egg-dropzone dropzone" data-name="{{ $name }}"<? if (isset($params) && is_array($params) && count($params)) { foreach($params as $key => $value) { echo ' data-' . $key . '="' . $value . '"'; } } ?>></div>
    <div class="superbox_" style="margin-top:10px;">

    	<input type="hidden" name="{{ $name }}[gallery_id]" value="{{ (isset($gallery) && is_object($gallery)) ? (int)$gallery->id : '0' }}" id="{{ $name }}_gallery_id" />

        @if (@is_object($gallery))
            <? $gallery_photos = $gallery->photos()->get(); ?>
            @if ($gallery_photos)
                <div class="photo-previews sortable">
                    @foreach ($gallery_photos as $photo)
                    <div class="photo-preview sortable_item" data-id="{{ $photo->id }}" style="background-image:url({{ URL::to($photo->thumb()) }});">
                        <a href="{{ URL::to($photo->path()) }}" target="_blank" title="Полноразмерное изображение" style="display:block; height:100%; color:#090; background:transparent"></a>
                        <a href="#" class="photo-delete" data-photo-id="{{ $photo->id }}" style="">Удалить</a>
                    </div>
                    @endforeach
                </div>
            @endif
        @endif

    </div>
    <div class="clear"></div>
