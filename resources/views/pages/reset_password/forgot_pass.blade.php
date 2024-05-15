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
                  @csrf
                    <div class="opt-tit">
                      <h2 class="text_center title">パスワードを忘れた方</h2>
                      <p class="text_center text_note">パスワードを忘れた方は、下記のボックスに、<br>受信可能なメールアドレスを入力して「送信する」ボタンをクリックしてください。</p>
                    </div>
                    <table class="table-style table-sp">
                      <tbody>
                        <tr>
                          <th>メールアドレス</th>
                          <td>
                            <input class="form-control" type="email" name="email" placeholder="メールアドレスをご入力ください" required>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="btn-wrapper">
                      <button type="submit" class="btn btn-lightgreen">送信する</button>
                    </div>
                  </form>
                </div>
              </div>>
@endsection

@section('custom_js')
<script>

</script>
@endsection