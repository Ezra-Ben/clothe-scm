<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
@if(trim($slot))
{{ Illuminate\Mail\Markdown::parse($slot) }}
@endif
</td>
</tr>
</table>
</td>
</tr>
