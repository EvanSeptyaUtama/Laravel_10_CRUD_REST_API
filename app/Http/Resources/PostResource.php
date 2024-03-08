<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

//Kelas postresource ini turunan dari kelas JsonResource
class PostResource extends JsonResource
{
    // Define properti (mendefinisikan properti)
    //properti status dan message = menyimpan status dan pesan yang akan
    //ditampilkan pada api
    //resource = menyimpan data yang akan dikirimkan dalam response API
    public $status;
    public $message;
    public $resource;
    //Method _construct = untuk mengatur nilai dari setiap parameter yang dipanggil
    public function __construct($status,  $message, $resource)
    {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    //method toArray = method yang diinplementasikan dari class JsonResource
    //method ini akan dipanggil jika objek PostResource diubah menjadi array
    //method ini juga akan mengembalikan sebuah array
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }
}
