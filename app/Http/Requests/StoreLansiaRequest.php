<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLansiaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $kecamatan = config('wilayah.kecamatan');
        $desaMap   = config('wilayah.desa');
        $years     = range(2015, (int)date('Y') + 1);
        $jenis     = array_keys(config('lansia.jenis_bantuan'));
        $sumber    = config('lansia.sumber_dana');

        return [
            'nik'           => ['required','digits:16','unique:lansias,nik'],
            'nama'          => ['required','string','max:100'],
            'jk'            => ['required', Rule::in(['L','P'])],
            'kecamatan'     => ['required', Rule::in($kecamatan)],
            'desa'          => ['required','string'],
            'jenis_bantuan' => ['required', Rule::in($jenis)],     // INSENTIF/PERMAKANAN
            'tahun'         => ['required', Rule::in($years)],
            'sumber_dana'   => ['required', Rule::in($sumber)],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function($v){
            $desaMap = config('wilayah.desa');
            $kec  = $this->input('kecamatan');
            $desa = $this->input('desa');
            if ($kec && $desa && ! in_array($desa, $desaMap[$kec] ?? [])) {
                $v->errors()->add('desa', 'Desa/Kelurahan tidak sesuai dengan kecamatan yang dipilih.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'digits'   => ':attribute harus :digits digit.',
            'unique'   => ':attribute sudah terdaftar.',
            'in'       => ':attribute tidak valid.',
            'max'      => ':attribute maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nik'           => 'NIK',
            'nama'          => 'Nama Lengkap',
            'jk'            => 'Jenis Kelamin',
            'kecamatan'     => 'Kecamatan',
            'desa'          => 'Desa/Kelurahan',
            'jenis_bantuan' => 'Jenis Bantuan',
            'tahun'         => 'Tahun',
            'sumber_dana'   => 'Sumber Dana',
        ];
    }
}
