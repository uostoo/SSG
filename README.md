# 🛠️ SSG - Sistema de Serviços Gerais

Em 2024, participei do desenvolvimento do **SSG** como **desenvolvedor back-end**, focando na lógica de negócios, integração com banco de dados, regras de agendamento e controle de acesso. O SSG é um sistema web com o objetivo de conectar prestadores de serviços domésticos (como encanadores, diaristas, eletricistas, entre outros) a clientes que buscam por esses serviços. O projeto foi orientado pelo professor **Daniel Vidal** como parte de nossas atividades práticas em desenvolvimento de sistemas.

## 🔍 Visão Geral

O SSG funciona como uma plataforma de agendamentos entre prestadores e clientes. Nele, os prestadores cadastram seus serviços e disponibilizam horários. Os clientes escolhem um serviço e solicitam o agendamento. Uma notificação é enviada ao prestador, que pode aceitar ou recusar. A resposta gera uma nova notificação ao cliente com o status atualizado.

### 🎯 Funcionalidades principais

- Cadastro e login de clientes e prestadores
- Criação e listagem de serviços
- Agendamento com controle de status: Pendente, Aceito ou Negado
- Histórico de serviços prestados com avaliações
- Sistema de notificação (resposta de agendamento)
- Estrutura de herança no banco de dados (cliente/prestador)

## 💻 Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP (procedural)
- **Banco de Dados**: MySQL
- **Servidor**: Apache (via XAMPP ou WAMP)

## 🧠 Modelagem do Banco de Dados

O sistema utiliza um banco relacional em MySQL com herança entre as tabelas `cliente` e `prestador`, e relacionamentos bem definidos para controle de serviços e agendamentos.

### Tabelas principais

- `cliente`: informações de login e identificação
- `prestador`: especialização do cliente
- `servicos`: serviços oferecidos
- `agendamentos`: registros de horários e status
- `servicos_prestados`: histórico e avaliações

📂 O script SQL está disponível neste repositório: [`ssg.sql`](./ssg.sql)
