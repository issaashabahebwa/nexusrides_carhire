{{-- Shared form fields for create & edit vehicle --}}
@php $v = $vehicle ?? null; @endphp

<div class="form-row form-row-3">
    <div class="form-group">
        <label class="form-label">Vehicle Name <span style="color:var(--red)">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $v?->name) }}" placeholder="e.g. Toyota Camry XLE" required>
        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Brand <span style="color:var(--red)">*</span></label>
        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
               value="{{ old('brand', $v?->brand) }}" placeholder="Toyota, BMW, Mercedes…" required>
        @error('brand')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Model <span style="color:var(--red)">*</span></label>
        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
               value="{{ old('model', $v?->model) }}" placeholder="Camry, X5, E-Class…" required>
        @error('model')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>

<div class="form-row form-row-3">
    <div class="form-group">
        <label class="form-label">Year <span style="color:var(--red)">*</span></label>
        <input type="number" name="year" class="form-control @error('year') is-invalid @enderror"
               value="{{ old('year', $v?->year) }}" min="1990" max="{{ date('Y')+1 }}" required>
        @error('year')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Plate Number <span style="color:var(--red)">*</span></label>
        <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror"
               value="{{ old('plate_number', $v?->plate_number) }}" placeholder="UAA 000X" required>
        @error('plate_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Color</label>
        <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
               value="{{ old('color', $v?->color) }}" placeholder="White, Black, Silver…">
        @error('color')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>

<div class="form-row form-row-3">
    <div class="form-group">
        <label class="form-label">Vehicle Type <span style="color:var(--red)">*</span></label>
        <select name="type" class="form-control form-select @error('type') is-invalid @enderror" required>
            @foreach(['sedan','suv','van','truck','bus','convertible','pickup'] as $t)
            <option value="{{ $t }}" {{ old('type',$v?->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Transmission <span style="color:var(--red)">*</span></label>
        <select name="transmission" class="form-control form-select @error('transmission') is-invalid @enderror" required>
            <option value="automatic" {{ old('transmission',$v?->transmission)=='automatic'?'selected':'' }}>Automatic</option>
            <option value="manual"    {{ old('transmission',$v?->transmission)=='manual'?'selected':'' }}>Manual</option>
        </select>
        @error('transmission')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Fuel Type <span style="color:var(--red)">*</span></label>
        <input type="text" name="fuel_type" class="form-control @error('fuel_type') is-invalid @enderror"
               value="{{ old('fuel_type', $v?->fuel_type ?? 'Petrol') }}" placeholder="Petrol, Diesel, Hybrid…" required>
        @error('fuel_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>

<div class="form-row form-row-3">
    <div class="form-group">
        <label class="form-label">Seats <span style="color:var(--red)">*</span></label>
        <input type="number" name="seats" class="form-control @error('seats') is-invalid @enderror"
               value="{{ old('seats', $v?->seats ?? 5) }}" min="1" max="50" required>
        @error('seats')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Price Per Day (UGX) <span style="color:var(--red)">*</span></label>
        <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror"
               value="{{ old('price_per_day', $v?->price_per_day) }}" min="0" step="1000" placeholder="150000" required>
        @error('price_per_day')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Status <span style="color:var(--red)">*</span></label>
        <select name="status" class="form-control form-select @error('status') is-invalid @enderror" required>
            @foreach(['available','hired','maintenance','retired'] as $s)
            <option value="{{ $s }}" {{ old('status',$v?->status ?? 'available') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>

<div class="form-row form-row-2">
    <div class="form-group">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
               value="{{ old('location', $v?->location) }}" placeholder="Kampala, Entebbe…">
        @error('location')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label">Vehicle Image</label>
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
               accept="image/jpeg,image/png,image/webp">
        @error('image')<span class="invalid-feedback">{{ $message }}</span>@enderror
        @if($v?->image)
        <div style="margin-top:.6rem;display:flex;align-items:center;gap:.8rem;">
            <img src="{{ $v->image_url }}" alt="Current" style="width:80px;height:55px;object-fit:cover;border-radius:var(--radius);">
            <span style="font-size:.82rem;color:#6c757d;">Current image. Upload a new one to replace.</span>
        </div>
        @endif
    </div>
</div>

<div class="form-group">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
              placeholder="Vehicle features, condition notes, etc.">{{ old('description', $v?->description) }}</textarea>
    @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>
