<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShippingFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $listCity = City::where(function ($query) use($request){
            if (!empty($request->get('search'))){
                $query->where('name', 'like', "%" . $request->get('search') . "%");
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('admin.city.index', compact('listCity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('admin.city.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:city,name'],
            'shipping_fee' => ['required', function ($attribute, $value, $fail) {
                if (!$this->isValidShippingFee($value)) {
                    $fail('Phí vận chuyển không hợp lệ.');
                }
            }],
        ]);

        try {
            $city = new City();
            $city->name = $data['name'];
            $city->code = $this->makeCityCode($data['name']);
            $city->shipping_fee = $this->normalizeShippingFee($data['shipping_fee']);
            $city->save();

            return redirect()->route('admin.cities.edit', $city->id)->with('success', 'Thêm thành công');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id): View
    {
        $city = City::find($id);

        if (empty($city)) {
            abort(404);
        }

        return view('admin.city.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'shipping_fee' => ['required', function ($attribute, $value, $fail) {
                if (!$this->isValidShippingFee($value)) {
                    $fail('Phí vận chuyển không hợp lệ.');
                }
            }],
        ]);

        try {
            $city = City::find($id);
            $data = $request->all();
            $data['shipping_fee'] = $this->normalizeShippingFee($request->get('shipping_fee', 0));

            $city->fill($data);

            $city->save();

            return redirect()->route('admin.cities.edit', $id)->with('success', 'Cập nhật thành công');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function normalizeShippingFee($value): float
    {
        $number = preg_replace('/[^\d.]/', '', (string) $value);

        return (float) ($number ?: 0);
    }

    private function isValidShippingFee($value): bool
    {
        if (strpos((string) $value, '-') !== false) {
            return false;
        }

        $number = preg_replace('/[^\d.]/', '', (string) $value);

        return $number !== '' && is_numeric($number) && (float) $number >= 0;
    }

    private function makeCityCode(string $name): string
    {
        $baseCode = Str::slug($name) ?: 'city';
        $code = $baseCode;
        $index = 1;

        while (City::where('code', $code)->exists()) {
            $code = $baseCode . '-' . $index;
            $index++;
        }

        return $code;
    }
}
