cep_correios
============

Retorna um JSON com os dados de determinado CEP

============

## Exemplos ##
Sem o Rewrite com .htaccess
Exemplo de uso:
correios.php?cep=00000000

Exemplo em funcionamento:
http://matheusdev.kd.io/correios.php?cep=00000000

Com o Rewrite com .htaccess (agradecimentos ao @icaromh2)
Exemplo de uso:
cep_correios/00000000

Exemplo em funcionamento:
http://matheusdev.kd.io/cep_correios/00000000

## Retorno ##

Se encontrar os dados para o CEP passdo, o retorno será:
```javascript
{
  "logadouro": "Rua Exemplo",
  "bairro": "Bairro Exemplo",
  "cidade": "Cidade Exemplo",
  "estado": "UF Exemplo"
}
```

Caso não encontre, o retorno será:
```javascript
{
  "erro": 1,
  "msg": "CEP não encontrado ou Serviço dos correios offline"
}
```
