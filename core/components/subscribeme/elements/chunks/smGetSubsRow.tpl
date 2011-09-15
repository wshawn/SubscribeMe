<li class="subscription" style="[[+expired:eq=`0`:then=`color: #000`:else=`color: #222; font-style: italic`]]">
    [[+product_name]], started [[+start]] [[+expired:eq=`0`:then=`will expire`:else=`expired`]] on [[+expires]]
    [[+active:eq=`1`:then=`
        <form method="post" action="[[~[[*id]]]]">
            <input type="hidden" name="sub_id" value="[[+sub_id]]" />
            <input type="submit" value="Cancel Payments" />
        </form>
    `]]
</li>