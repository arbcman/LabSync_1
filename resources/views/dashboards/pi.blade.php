<section style="padding: 20px; border: 1px solid #ccc;">
    <h2>Add New Researcher</h2>
    @if (session('success'))
        <div
            style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <input type="radio" name="user_role" value="PI" onchange="toggleFields('PI')" required> PI
        <input type="radio" name="user_role" value="Lab_Manager" onchange="toggleFields('LabM')" required> Lab
        Manager
        <br><br>

        <label>User Name</label>
        <input type="text" name="user_name" placeholder="Name" required><br>

        <label>User Email</label>
        <input type="email" name="user_email" placeholder="name@email.com" required><br>

        <label>Expiry Date</label>
        <input type="date" name="expiry_date" placeholder="yyyy-mm-dd" required><br>

        <label>User Password</label>
        <input type="password" name="user_pass" placeholder="123456" required> <br>

        <!-- PI Fields -->
        <div id="pi_fields" style="display:none;">
            <label>Budget Limit</label>
            <input type="number" id="budget_input" name="budget_limit" placeholder="Budget Limit">
        </div>

        <!-- LabManager Fields -->
        <div id="labm_fields" style="display:none;">
            <label>Lab Locations</label>
            <input type="text" id="lab_input" name="lab_locations" placeholder="Lab Locations">
        </div>

        <button type="submit" style="margin-top: 10px;">Add User</button>
    </form>
</section>