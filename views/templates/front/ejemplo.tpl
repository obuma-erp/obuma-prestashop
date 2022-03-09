{extends file='page.tpl'}
{block name='page_content_container'}
<h1>
{l s='Estas son las categorias' mod='obuma'} {l s='Cantidad :' mod='obuma'} {$cantidad_categorias}
</h1>

<ul>
{foreach from=$categorias item=cat}

<li>{$cat['name']}</li>
{/foreach}
</ul>

{$modulo["name"]}
{/block}
