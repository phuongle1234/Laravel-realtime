@extends('layouts.register.index')

<!-- section('custom_css')
<script src="//cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
endsection -->
@section('class_body','regist forgotpass')

@section('content')


              <div class="login-bottom">
                <div class="login-bottom-content">
                  @include('component.register.alert')
                  <form class="loginbox_c full-width" method="post">
                  @method('PUT')
                  @csrf
                    <div class="opt-tit">
                      <h2 class="text_center title">パスワード再設定</h2>
                      <p class="text_center text_note">パスワードの再設定は、下記のボックスに、<br>新しいパスワードを入力して「登録する」ボタンをクリックしてください。</p>
                    </div>
                    <table class="table-style table-sp">
                      <tbody>
                        <tr>
                          <th>パスワード</th>
                          <td>
                            <input maxlength="12"  minlength="6" class="form-control" type="password" name="password" placeholder="パスワード（6文字以上の半角英数字）" required>
                          </td>
                        </tr>
                        <tr>
                          <th>パスワード </br>（確認用）</th>
                          <td>
                            <input maxlength="12"  minlength="6" class="form-control" type="password" name="c_password" placeholder="パスワード（6文字以上の半角英数字）" required>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="btn-wrapper">
                      <button type="submit" class="btn btn-lightgreen">登録</button>
                    </div>
                  </form>
                </div>
              </div>
@endsection

@section('custom_js')
<script>

</script>
@endsection