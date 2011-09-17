<tr class="[[+expired:notempty=`expired`]]">
  <td>[[+sub_id]]</td>
  <td>[[+product_name]]</td>
  <td>[[+start]]</td>
  <td>[[+expires]]</td>
  <td>[[+pp_profileid:notempty=`
         PayPal Recurring ([[+pp_profileid]]</a>)
      `:empty=`Manual`]]</td>
  <td>[[+active:eq=`1`:then=`
         Yes[[+pp_profileid:notempty=`, <a href="[[~43? &sub_id=`[[+sub_id]]`]]" title="Cancel Recurring Payments">cancel</a>`]]
      `:else=`No`]]</td>
</tr>
