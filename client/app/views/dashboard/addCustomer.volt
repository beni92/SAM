<h1>Add new customer</h1>
<div style="float: left; width: 200px">
{{ form('dashboard/addCustomer', 'method':'post') }}
    <label for="loginName">Login Name</label>
    {{ text_field("loginName", "size":45, "placeholder":"Login Name ...") }}

    <label for="firstname">Forename</label>
    {{ text_field("firstname", "size":45, "placeholder":"Forename ...") }}

    <label for="lastname">Surname</label>
    {{ text_field("lastname", "size":45, "placeholder":"Surname ...") }}

    <label for="address">Address</label>
    {{ text_field("address", "size":45, "placeholder":"Address ...") }}

    <label for="phone">Phone</label>
    {{ text_field("phone", "size":45, "placeholder":"Phone ...") }}

    <label for="password">Password</label>
    {{ password_field("password", "placeholder":"Password ...") }}
    <input type="hidden" class="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
    {{ submit_button("Add") }}
{{ end_form() }}
</div>