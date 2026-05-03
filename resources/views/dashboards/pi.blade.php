<section style="padding: 20px; border: 1px solid #ccc;">
    <h2>Add New Researcher</h2>
    @if (session('success'))
        <div
            style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('pi.researcher.store') }}">
        @csrf

        <br>

        <label>Researcher Name</label>
        <input type="text" name="user_name" placeholder="Name" required><br>

        <label>Researcher Email</label>
        <input type="email" name="user_email" placeholder="name@email.com" required><br>

        <label>Researcher Password</label>
        <input type="password" name="user_pass" placeholder="123456"> <br>

        <label>Expiry Date</label>
        <input type="date" name="expiry_date" placeholder="yyyy-mm-dd" required><br>

        <label>Academic Level</label>
        <input type="text" name="academic_level" placeholder="PhD" required><br>

        <label>Clearance Level</label>
        <input type="number" name="clearance_level" min="0" max="3"><br>
        <button type="submit" style="margin-top: 10px;">Add/Update Researcher</button>
    </form>
</section>

<x-logout-btn />