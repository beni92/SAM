{% if customer is defined and customer is not empty %}
<h2>Create new Depot for {{ customer.getLoginName() }}</h2>
<ul>
    <li>Max Budget: $ <?php echo(number_format($customer->getBudget(),2 )) ?></li>
</ul>
<div style="float: left; width: 200px">
    {{ form("dashboard/customer/" ~ customer.getLoginName(), "method":"POST") }}
    <label for="Budget">Budget in $</label>
    {{ text_field("budget", "placeholder":"Set your Budget ..") }}
    <input type="hidden" name="newDepot" value="new">
    {{ submit_button("Add") }}
    {{ end_form() }}
</div>
{% else %}
    <h1>401</h1>
{% endif %}