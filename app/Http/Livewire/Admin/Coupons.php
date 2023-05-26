<?php

namespace App\Http\Livewire\Admin;

use App\Models\Coupon;
use Livewire\Component;

class Coupons extends Component
{
    // attributes
    public $showModal;
    public $newCoupon = ['id' => null, 'amount_euro' => null, 'amount_point' => null, 'active'=>null];

    // validation rules
    protected function rules()
    {
        return[
            'newCoupon.amount_euro' => 'required|min:1|max:30',
            'newCoupon.amount_point' => 'required|min:1|max:30',
        ];
    }

    // validation attributes
    protected $validationAttributes = [
        'newCoupon.amount_euro' => 'aantal euro',
        'newCoupon.amount_point' => 'aantal punten',
        'newCoupon.active' => 'activiteit',
    ];

    // listen to the delete-coupon event
    protected $listeners = [
        'delete-coupon' => 'deleteCoupon',
    ];

    // reset coupon
    public function resetNewCoupon()
    {
        $this->reset('newCoupon');
        $this->resetErrorBag();
    }

    // set/reset $newCoupon and validation
    public function setNewCoupon(Coupon $coupon = null)
    {
        $this->resetErrorBag();

        if ($coupon) {
            $this->newCoupon['id'] = $coupon->id;
            $this->newCoupon['amount_euro'] = $coupon->amount_euro;
            $this->newCoupon['amount_point'] = $coupon->amount_point;
            $this->newCoupon['active'] = $coupon->active;
        } else {
            $this->reset('newCoupon');
        }
        $this->showModal = true;
    }

    // create a new coupon
    public function createCoupon()
    {
        // validate the rules
        $this->validate($this->rules());

        // Create the new coupon
        $coupon = Coupon::create([
            'id' => trim($this->newCoupon['id']),
            'amount_euro' => trim($this->newCoupon['amount_euro']),
            'amount_point' => trim($this->newCoupon['amount_point']),
            'active' => boolval($this->newCoupon['active'])
        ]);

        $this->resetNewCoupon();
        $this->showModal = false;

        // Toast success message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De Kortingsbon is toegevoegd",
        ]);
    }

    // update an existing coupon
    public function updateCoupon(Coupon $coupon)
    {
        // validate the rules
        $this->validate($this->rules());

        // update the coupon
        $coupon->update([
            'id' => $this->newCoupon['id'],
            'amount_euro' => $this->newCoupon['amount_euro'],
            'amount_point' => $this->newCoupon['amount_point'],
            'active' => $this->newCoupon['active'],
        ]);

        $this->showModal = false;

        // Toast success message
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "De kortingsbon is aangepast",
        ]);
    }

    // delete an existing coupon
    public function deleteCoupon(Coupon $coupon)
    {
        $coupon->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'danger',
            'html' => "De kortingsbon is verwijderd",
        ]);
    }

    // show coupon creating popup
    public function showCoupon()
    {
        $this->reset('newCoupon');
        $this->showModal = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $coupons = Coupon::withCount('orders')
            ->get();
        return view('livewire.admin.coupons', compact('coupons'))
            ->layout('layouts.projectPHP', [
                'description' => 'Kortingsbonnen beheren',
                'title' => 'Kortingsbonnen beheren',
            ]);
    }
}
