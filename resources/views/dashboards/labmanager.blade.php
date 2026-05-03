<h1> YOU ARE LabManager </h1>

<!-- DELETE SECTION -->
<section style="padding: 20px; border: 1px solid #ccc; margin-bottom: 20px;">
    <h2>Delete Equipment</h2>
    <form method="POST" action="{{ route('LabM.equipment.destroy') }}">
        @csrf
        @method('DELETE')
        <label>Equipment ID</label>
        <input type="text" name="equipment_id" placeholder="1" required>
        <button type="submit">DELETE Equipment</button>
    </form>
</section>

<hr>

<!-- ADD SECTION -->
<section style="padding: 20px; border: 1px solid #ccc;">
    <h2>Add New Equipment</h2>
    @if (session('success'))
        <div
            style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('LabM.equipment.store') }}">
        @csrf
        <br>
        <label>Equipment Name</label>
        <input type="text" name="equipment_name" placeholder="Name" required><br>

        <label>Equipment status</label>
        <input type="radio" name="equipment_status" value="Available" required>Available
        <input type="radio" name="equipment_status" value="In Use" required> In Use
        <input type="radio" name="equipment_status" value="Maintenance" required> Maintenance
        <br><br>

        <label>Hourly Rate</label>
        <input type="number" step="0.01" name="hourly_rate" placeholder="200.50" required><br>

        <label>Required Clearance</label>
        <input type="number" name="required_clearance" placeholder="1" min="0" max="3" required> <br>


        <button type="submit" style="margin-top: 10px;">Add/Update Equipment</button>
    </form>
</section>

<x-logout-btn />
