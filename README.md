cep_correios
============

Retorna um JSON com os dados de determinado CEP

============

Exemplo de uso:
correios.php?cep=00000000

Exemplo em funcionamento:
http://matheusdev.kd.io/correios.php?cep=00000000

============
Retorno

Se encontrar os dados para o CEP passdo, o retorno será:
{
  "logadouro": "Rua Exemplo",
  "bairro": "Bairro Exemplo",
  "cidade": "Cidade Exemplo",
  "estado": "UF Exemplo"
}

Caso não encontre, o retorno será:
{
  "erro": 1,
  "msg": "CEP não encontrado ou Serviço dos correios offline"
}
