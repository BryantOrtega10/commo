<form action="{{route("files.upload")}}" method="post" enctype="multipart/form-data"  class="dropzone" id="file-upload-form">
    @csrf
    <input type="hidden" name="file" id="file"/>
    @isset($customer_id)
        <input type="hidden" name="customer_id" value="{{$customer_id}}" />
    @endisset
    <div class="dz-message needsclick">    
        <img src="{{asset('imgs/upload.png')}}"><br>
        Drop your files here or <b>Browse</b> <br>
        Support: JPG, JPEG, PNG, PDF
    </div>
</form>