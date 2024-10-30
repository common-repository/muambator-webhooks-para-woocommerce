=== Muambator Webhooks para WooCommerce ===
Contributors: devopsbode
Tags: shipping, delivery, woocommerce, correios, muambator, webhooks, entregas
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.1.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integration with Muambator PRO Webhooks

== Description ==

Com o plugin Muambator Webhooks para WooCommerce, você pode exportar facilmente os códigos de rastreio de seus pedidos para sua conta PRO do Muambator e assim receber informações dos rastreamentos diretamente no WooCommerce.

Você também pode avisar seus clientes das movimentações da encomenda utilizando o email personalizado do Muambator.

= Instalação: =

Siga nosso passo-a-passo na aba [Installation](https://wordpress.org/plugins/muambator-webhooks-para-woocommerce/#installation).

= Compatibilidade =

Para utilizar esta integração com o Muambator Webhooks você irá necessitar de WooCommerce (3.0 ou superior), além de um assinatura de Muambator PRO.

Como padrão o plugin utiliza as informações de códigos de rastreio do plugin WooCommerce Correios (3.7 ou superior), porém também é possível utilizar um campo de meta dados do pedido.

= Dúvidas sobre o plugin? =

Algumas dúvidas podem ser esclarecidas na nossa sessão de [FAQ](https://wordpress.org/plugins/muambator-webhooks-para-woocommerce/#faq).
Se a dúvida não for sanada mesmo assim, mande uma [mensagem para o Flecha](https://www.muambator.com.br/contato/) que ele sabe como ajudar! :D

== Installation ==

1. Instale pelo instalador do Wordpress ou coloque os arquivos do plugin na pasta wp-content/plugins.
2. Ative o plugin na página de plugins do Wordpress.

= Requerimentos: =

* [WooCommerce](https://wordpress.org/plugins/woocommerce/) (v3.4 ou superior)
* [Conta Muambator PRO](https://www.muambator.com.br/)

**Opcional:**

* [WooCommerce Correios](https://wordpress.org/plugins/woocommerce-correios/) (v3.7 ou superior)

= Configurações do plugin: =

O acesso das configurações do plugin estão contidas em Configurações > Muambator Webhooks.

Lá você irá encontrar os passos para configurar as notificações Webhooks assim como as opções para ativar envio de notificações aos clientes e detalhes da exportação das ordens de rastreio no Muambator.

= Importando códigos no Muambator =

Para transportar os códigos de rastreio dos seus pedido para o Muambator, é necessário gerar um CSV que vai conter as informações necessárias. O download deste CSV estará disponível na página WooCommerce > CSV Muambator junto com uma pre-visualização do que será exportado.

**OBS:** Como padrão, o plugin irá pegar os códigos de ordens criadas nos últimos 4 meses e com os status "Processando" e "Concluído", porém isto é possível modificar nas configurações do plugin

== Frequently Asked Questions ==

= Qual é a licença do plugin? =

Este plugin esta licenciado como GPL.

= O que eu preciso para utilizar este plugin? =

* WooCommerce 3.0 ou posterior.
* Uma assinatura ativa do Muambator PRO.

**Opcional:**

* WooCommerce Correios 3.7 ou superior.

= Como faço a inclusão dos meus pacotes no Muambator? =

Para transportar os códigos de rastreio dos seus pedido para o Muambator, é necessário gerar um CSV que vai conter as informações necessárias. O download deste CSV estará disponível na página WooCommerce > CSV Muambator junto com uma pre-visualização do que será exportado.

**OBS:** Como padrão, o plugin irá pegar os códigos de ordens criadas nos últimos 4 meses e com os status "Processando" e "Concluído", porém isto é possível modificar nas configurações do plugin

= Não quero utilizar o plugin WooCommerce Correios, como faço? =

Nosso plugin foi feito para trabalhar diretamente com as informações do plugin WooCommerce Correios porém também conseguimos ler informações de outros campos de meta dados dos pedidos.

Para configurar o campo que deseja utilizar, basta acessar a tela de Configurações > Muambator Webhooks e inserir o nome do campo em "Campo Meta a ser utilizado".

**Obs:** Outros plugins de entrega e informações de rastreamento também utilizam o espaço de meta dados porém de forma escondida. Caso queira usar informações de outros plugins, basta utilizar o nome do campo meta específico.

= Há alguma forma automatizada para importar os códigos no Muambator? =

Sim, porém apenas utilizando a API do Muambator, um serviço pago não incluso na assinatura PRO. Para mais detalhes entre em [contato com o Flecha e sua equipe](https://www.muambator.com.br/contato/)

= Meus pedidos/códigos não aparecem no CSV. O que está acontecendo? =

Por padrão, o plugin irá procurar códigos de ordens criadas nos últimos 4 meses e com os status "Processando" e "Concluído", porém isto pode ser modificado nas configurações do plugin.

Caso as ordens estejam dentro dos parametros configurados e ainda não apareçam no CSV, pode entrar em contato no [nosso suporte](https://www.muambator.com.br/contato/) que iremos te ajudar! :)

= Outras dúvidas ou problemas com o plugin? =

Manda uma [mensagem para o Flecha](https://www.muambator.com.br/contato/) que ele ajuda! :D

== Screenshots ==

1. Tela de configurações do plugin.
2. Tabela com link para download das ordens a serem exportadas para o Muambator.
3. Detalhes de um pedido com a última movimentação dos pacotes relacionados.
4. Rastreio completo dos pacotes de um pedido.

== Changelog ==

= 1.1.0 - 2018/10/11 =

- Agora suportamos não só o plugin WooCommerce Correios, mas qualquer valor em um campo de meta dados do pedido! :D

= 1.0.4 - 2018/10/10 =

- Leve correção para tentar solucionar algumas vezes que linhas em branco são adicionadas no inicio do arquivo CSV.

= 1.0.3 - 2018/09/06 =

- Primeira versão do plugin! :D
- Recebimento das mensagens de webhooks.
- Tela para extrair um CSV para inserir no Muambator.
- Tela com detalhes de rastreio de cada um dos códigos do pedido.
- Configurações dos pacotes que gostaria de exportar para o Muambator.