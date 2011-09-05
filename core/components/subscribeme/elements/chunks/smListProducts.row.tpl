<li class="product" style="width: 450px; border: 2px solid #333; list-style-type: none; padding: 15px;">
    <form class="newsubscription" action="[[~[[*id]]]]" method="POST">
        <input type="hidden" name="product" value="[[+product_id]]" / >
        <input type="submit" value="Subscribe!" style="float: right;" />
        <h3>[[+name]] ([[+currency]] [[+amount_total]] / [[+periods:gt=`1`:then=`[[+periods]] [[+period]]`:else=`[[+period]]`]])</h3>
        <p>[[+description]]</p>
    </form>
</li>