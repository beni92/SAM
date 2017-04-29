{% if customer is defined and customer is not empty %}
<h1>Change Budget from {{ customer.getLoginName() }}</h1>
<ul>
    <li>Current Budget: $ <?php echo(number_format($customer->getBudget(), 2)) ?></li>
</ul>
{{ form("dashboard/customer/" ~ customer.getLoginName()) }}
    <label for="cash" >Amount to add in $</label>
    {{ text_field("cash", "placeholder":"How much..") }}
    <input type="hidden" name="changeBudget" value="add">
    {{ submit_button("Add") }}
{{ end_form() }}
{% else %}
<h1>401</h1>
{% endif %}