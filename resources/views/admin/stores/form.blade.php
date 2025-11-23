<div class="form-group">
    <label>Store Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $store->name ?? '') }}" placeholder="e.g., Aziz Super Market" required>
</div>

<div class="form-group">
    <label>Division</label>
    <input type="text" name="division" class="form-control" value="{{ old('division', $store->division ?? '') }}" placeholder="e.g., Dhaka Division" required>
</div>

<div class="form-group">
    <label>Address</label>
    <textarea name="address" class="form-control" rows="3" placeholder="Shop no - 59 (Ground Floor), Aziz Super Market, Shahbag, Dhaka" required>{{ old('address', $store->address ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Phone Number</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $store->phone ?? '') }}" placeholder="01771448882" required>
</div>

<div class="form-group">
    <label>Email (Optional)</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $store->email ?? '') }}">
</div>