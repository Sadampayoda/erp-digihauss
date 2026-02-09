@extends('template.dashboard')

@section('content')
<div class="flex flex-col w-full h-full overflow-y-auto custom-scroll gap-6 p-4 lg:p-6">

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 flex flex-col gap-6">

            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-semibold text-slate-700">Product Identity</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500">Status</span>
                        <input type="checkbox" class="w-10 h-5 accent-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-input-text
                        name="item_code"
                        label="Item Code"
                        placeholder="IP13-PRO-128-GR"
                    />

                    <x-input-text
                        name="name"
                        label="Product Name"
                        placeholder="iPhone 13 Pro 128GB Graphite"
                    />
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="text-lg font-semibold text-slate-700 mb-5">Technical Specifications</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <x-input-select name="brand" label="Brand">
                        <option value="Apple">Apple</option>
                    </x-input-select>

                    <x-input-select name="series" label="Series">
                        <option value="iPhone 13">iPhone 13</option>
                    </x-input-select>

                    <x-input-select name="model" label="Model">
                        <option value="13 Pro">13 Pro</option>
                    </x-input-select>

                    <x-input-select name="storage_gb" label="Storage">
                        <option value="128">128 GB</option>
                        <option value="256">256 GB</option>
                    </x-input-select>

                    <x-input-select name="ram_gb" label="RAM">
                        <option value="6">6 GB</option>
                        <option value="8">8 GB</option>
                    </x-input-select>

                    <x-input-select name="color" label="Color">
                        <option value="Graphite">Graphite</option>
                        <option value="Silver">Silver</option>
                    </x-input-select>

                    <x-input-select name="network" label="Network Status">
                        <option value="Factory Unlocked">Factory Unlocked</option>
                    </x-input-select>

                    <x-input-select name="region" label="Region Spec">
                        <option value="LL/A">LL/A (USA)</option>
                        <option value="ID/A">ID/A (Indonesia)</option>
                    </x-input-select>

                    <x-input-text
                        name="variant_code"
                        label="Variant Code"
                        placeholder="A2638"
                    />
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="text-lg font-semibold text-slate-700 mb-5">Condition & Grading</h2>

                <div class="flex flex-wrap gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="1" class="hidden peer">
                        <div class="px-4 py-2 rounded-lg border peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600">
                            New / Sealed
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="2" class="hidden peer" checked>
                        <div class="px-4 py-2 rounded-lg border peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600">
                            Grade A (Like New)
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="condition" value="3" class="hidden peer">
                        <div class="px-4 py-2 rounded-lg border peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600">
                            Grade B (Minor Wear)
                        </div>
                    </label>
                </div>
            </div>

        </div>

        <div class="flex flex-col gap-6">

            <div class="bg-white rounded-xl border p-5">
                <div class="border-2 border-dashed rounded-lg h-48 flex flex-col items-center justify-center gap-2 text-slate-400">
                    <span class="text-sm">Upload</span>
                    <span class="text-xs">PNG, JPG up to 5MB</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
