@extends('layouts.admin.index')
@section('body_class','p-video detail')
@section('class_main','p-content-video detail')

@section('content')
<div class="table-style mt00 table-shawdow">
  <form action="" method="post">
    @csrf
    @method('PUT')
    <table>
      <tbody>
        <tr>
          <th>投稿日時</th>
          <td>
            <input class="form-control disable" type="text" maxlength="30" value="{{$item->created_at->format('Y年m月d日 H:i')}}">
          </td>
        </tr>
        <tr>
          <th>講師名</th>
          <td>
            <input class="form-control disable" type="text" maxlength="30" value="{{$item->user->code}}">
          </td>
        </tr>

        <tr>
          <th>動画</th>
          <td>
            <div class="list-thumbnail">
                 <iframe src="{{ $item->player_embed_url }}"  width="600"  frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
            </div>
          </td>
        </tr>

        <tr>
          <th>動画サムネイル</th>
          <td>
            <div class="list-thumbnail">
              <figure class="thumbnail"><img src="{{$item->thumbnail}}" alt="{{$item->title}}"></figure>
            </div>
          </td>
        </tr>



        <tr>
          <th>動画タイトル</th>
          <td>
               <input class="form-control" style="background: unset; border: 1px solid #D6D7D6 !important;" name="title" type="text" maxlength="50" required value="{{$item->title }}">
          </td>
        </tr>
        <tr>
          <th>動画説明文</th>
          <td>
            <textarea name="description" style="background: unset; border: 1px solid #D6D7D6 !important;" class="form-control" maxlength="10000" cols="30" rows="5" required>{{ $item->description }}</textarea>
          </td>
        </tr>

      @if( $item->path )
        <tr>
          <th>添付資料</th>
          <td>
            <button class="btn-img"><img src="{{ asset('common_img/icon-pdf.svg')}}" >
                <a  href="{{ route( 'link_dowload' , ['name' => $item->name_path ] ) }}"  download>
                <span>{{$item->name_path}}​</span></button>
                </a>
          </td>
        </tr>
      @endif



      </tbody>
    </table>

    <div class="fec">
                <button type="submit" class="btn btn-primary btn-custom mr10">登録</button>
                <a class="btn btn-danger btn-custom" href="{{ route('admin.video_management.list') }}">戻る</a>
    </div>
  </form>
  </div>
  @endsection
  @section('custom_js')
<script>

  $("iframe").on("load", (e) => {

      _iframe  = e.target;

      if( _iframe.getAttribute('src').search( 'transcode.svg' ) < 0)
        return false;
      _height = _iframe.getAttribute('height');
      _width = _iframe.getAttribute('width');

      _parent = $(_iframe).parent();

      $(_parent).html( $(`<figure width="${_width}" height="${_height}" ><img src='${ _iframe.getAttribute('src') }' style="object-fit: contain; width: 100%; height: 100%;" /> </figure>`) );

      $(_iframe).remove();

});

</script>
@endsection