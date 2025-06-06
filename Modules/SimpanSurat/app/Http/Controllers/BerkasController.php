<?php

namespace Modules\SimpanSurat\Http\Controllers;

use App\Helpers\Fungsi;
use App\Helpers\GetSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SimpanSurat\Models\Berkas;
use RealRashid\SweetAlert\Facades\Alert;

class BerkasController extends Controller
{
  public function index()
  {
    Fungsi::hakAkses('/simpan/berkas');
    $alert = 'Delete Data!';
    $text = "Are you sure you want to delete?";
    confirmDelete($alert, $text);

    $title = 'Data Berkas';
    $data = Berkas::latest()->get();
    // dd($data);
    return view(
      'simpansurat::/berkas/index',
      [
        'title' => $title,
        'data' => $data,
      ]
    );
  }
  public function create()
  {
    Fungsi::hakAkses('/simpan/berkas');

    $title = "Tambah Data Berkas";
    return view(
      'simpansurat::berkas/create',
      [
        'title' => $title,
      ]
    );
  }
  public function store(Request $request)
  {
    $data = $request->all();

    $data = $request->validate([
      'nama_berkas' => 'required',
      'kode_berkas' => 'required',
      'tanggal_upload' => 'required',
      'deskripsi' => 'required',
      'lampiran_1' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
      'lampiran_2' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
      'lampiran_3' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
      'lampiran_4' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
      'lampiran_5' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx',
    ]);

    if ($request->hasFile('lampiran_1')) {
      $file = $request->file('lampiran_1');
      $extension = $file->getClientOriginalExtension();
      $newFileName = $request->nama_surat . '_lampiran_1_' . now()->timestamp . '.' . $extension;
      $file->move(public_path('/berkas'), $newFileName);
      $data['lampiran_1'] = $newFileName;
    }
    if ($request->hasFile('lampiran_2')) {
      $file = $request->file('lampiran_2');
      $extension = $file->getClientOriginalExtension();
      $newFileName = $request->nama_surat . '_lampiran_2_' . now()->timestamp . '.' . $extension;
      $file->move(public_path('/berkas'), $newFileName);
      $data['lampiran_2'] = $newFileName;
    }
    if ($request->hasFile('lampiran_3')) {
      $file = $request->file('lampiran_3');
      $extension = $file->getClientOriginalExtension();
      $newFileName = $request->nama_surat . '_lampiran_3_' . now()->timestamp . '.' . $extension;
      $file->move(public_path('/berkas'), $newFileName);
      $data['lampiran_3'] = $newFileName;
    }
    if ($request->hasFile('lampiran_4')) {
      $file = $request->file('lampiran_4');
      $extension = $file->getClientOriginalExtension();
      $newFileName = $request->nama_surat . '_lampiran_4_' . now()->timestamp . '.' . $extension;
      $file->move(public_path('/berkas'), $newFileName);
      $data['lampiran_4'] = $newFileName;
    }
    if ($request->hasFile('lampiran_5')) {
      $file = $request->file('lampiran_5');
      $extension = $file->getClientOriginalExtension();
      $newFileName = $request->nama_surat . '_lampiran_5_' . now()->timestamp . '.' . $extension;
      $file->move(public_path('/berkas'), $newFileName);
      $data['lampiran_5'] = $newFileName;
    }


    if (!empty($request->id)) {
      $updateData = Berkas::findOrFail($request->id);
      $data['updated_by'] = Auth::user()->username;
      $updateData->update($data);
      Alert::success('Success', 'Data berhasil diupdate');
      return redirect()->route('berkas.index');
    }

    $data['created_by'] = Auth::user()->username;
    Berkas::create($data);
    Alert::success('Success', 'Data berhasil disimpan');
    return redirect()->route('berkas.index');
  }
  public function view($id)
  {
    Fungsi::hakAkses('/simpan/berkas');
    $data = Berkas::findOrFail($id);
    $base_url = GetSettings::getBaseUrl();

    $url_lampiran1 = $data->lampiran_1 ? $base_url . 'berkas/' . $data->lampiran_1 : '';
    $url_lampiran2 = $data->lampiran_2 ? $base_url . 'berkas/' . $data->lampiran_2 : '';
    $url_lampiran3 = $data->lampiran_3 ? $base_url . 'berkas/' . $data->lampiran_3 : '';
    $url_lampiran4 = $data->lampiran_4 ? $base_url . 'berkas/' . $data->lampiran_4 : '';
    $url_lampiran5 = $data->lampiran_5 ? $base_url . 'berkas/' . $data->lampiran_5 : '';

    // dd($url_lampiran1);
    return response()->json([
      'lampiran1' => $url_lampiran1,
      'lampiran2' => $url_lampiran2,
      'lampiran3' => $url_lampiran3,
      'lampiran4' => $url_lampiran4,
      'lampiran5' => $url_lampiran5,
      'nama' => $data->nama_berkas,
    ]);
  }
  public function edit($id)
  {
    $title = "Update Data Data Berkas";
    Fungsi::hakAkses('/simpan/berkas');
    $data = Berkas::findOrFail($id);
    return view(
      'simpansurat::berkas.edit',
      [
        'data' => $data,
        'title' => $title,
      ]
    );
  }
  public function destroy($id)
  {
    Fungsi::hakAkses('/simpan/berkas');

    $data = Berkas::findOrFail($id);
    $data->deleted_by = Auth::user()->username;
    // if ($data->karyawans()->count() > 0) {
    //   Alert::error('Oops....', 'Data tidak dapat dihapus karena memiliki data surat');
    //   return redirect()->route('klasifikasi.index');
    // }
    $data->save();
    $data->delete();
    Alert::success('Success', 'Data berhasil dihapus');
    return redirect()->route('berkas.index');
  }
}
