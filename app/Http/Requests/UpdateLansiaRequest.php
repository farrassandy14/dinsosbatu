<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLansiaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $kecamatan = config('wilayah.kecamatan');
        $years     = range(2015, (int)date('Y') + 1);
        $jenis     = array_keys(config('lansia.jenis_bantuan'));
        $sumber    = config('lansia.sumber_dana');

        // param route model: {lansium}
        $currentId = optional($this->route('lansium'))->id ?? optional($this->route('lansia'))->id;

        return [
            'nik'           => ['required','digits:16', Rule::unique('lansias','nik')->ignore($currentId)],
            'nama'          => ['required','string','max:100'],
            'jk'            => ['required', Rule::in(['L','P'])],
            'kecamatan'     => ['required', Rule::in($kecamatan)],
            'desa'          => ['required','string'],
            'jenis_bantuan' => ['required', Rule::in($jenis)],
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
}
