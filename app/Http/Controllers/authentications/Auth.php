<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth as AuthUser;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Auth extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login', ['pageConfigs' => $pageConfigs]);
  }

  public function auth_action(Request $request)
  {

    // // Mencoba autentikasi berdasarkan input
    if (AuthUser::attempt(['username' => $request->username, 'password' => $request->password])) {
      $user = UserModel::find(AuthUser::id());

      // Mengambil data pengaturan tanpa stored procedure
      $data = DB::table('settings')->first();

      $request->session()->put('id', $user->id);
      $request->session()->put('username', $user->username);
      $request->session()->put('nama', $user->nama);
      $request->session()->put('namaSistem', $data->nama);
      $request->session()->put('singkatan', $data->singkatan);
      $request->session()->put('alamat', $data->alamat);
      $request->session()->put('tagLine', $data->tag_line);
      $request->session()->put('logo', $data->logo);

      return redirect('dashboard');
    } else {
      // Autentikasi gagal, kembalikan ke halaman login dengan pesan kesalahan
      return back()->withErrors([
        'login' => 'Username atau password yang Anda masukkan salah.',
      ]);
    }
  }

  public function logout(Request $request)
  {
    AuthUser::logout();
    // Invalidate session and regenerate token
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->withHeaders([
      'Cache-Control' => 'no-cache, no-store, must-revalidate',
      'Pragma' => 'no-cache',
      'Expires' => '0',
    ]);
  }
}
