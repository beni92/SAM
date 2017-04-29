<h1>Customers</h1>
{{ form("dashboard/customer/search", "method":"GET") }}
    <label for="search">Search</label>
    {{ text_field("search", "placeholder":"Search ...") }}
    {{ submit_button("Find") }}
{{ end_form() }}
{% if customers|length > 0 %}
    <ul>
        {% for customer in customers %}
            <li><a href="{{ url('dashboard/customer/'~customer.getLoginName()) }}">{{ customer.getFirstname() }} {{ customer.getLastname() }}</a></li>
        {% endfor %}
    </ul>
{% else %}
    <h2>No customers found</h2>
{% endif %}