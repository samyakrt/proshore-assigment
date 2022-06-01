<?php
namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'joined' => $this->joined,
            'department_id' => $this->department_id,
            'role_id' => $this->role_id,
            'manager_id' => $this->manager_id,
            'join_date' => ($this->join_date)->format('Y-m-d'),
            'joined_ago' => $this->joined_ago,
            'department' => $this->whenLoaded('department'),
            'role' => $this->whenLoaded('role'),
        ];
    }
}
