{% if bank is defined and bank is not empty %}
<h1>Bank: {{ bank.getName() }}</h1>
<ul>
    <li>Volume: $ <?php echo(number_format($bank->getVolume(), 2)) ?></li>
</ul>
{% else %}
    <h1>401</h1>
{% endif %}