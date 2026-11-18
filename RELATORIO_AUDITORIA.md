# Relatório de auditoria acadêmica

Data da verificação: 17 de novembro de 2026.

| Requisito | Situação | Evidência |
| --- | --- | --- |
| PHP | Atendido | Código PHP organizado em páginas, configuração e inclusões. |
| Estudante com mais de 5 campos | Atendido | Nome, CPF, e-mail, telefone, data de nascimento e registro acadêmico. |
| Endereço com 5 ou mais campos | Atendido | Rua, número, complemento, bairro, cidade, estado e CEP. |
| Curso com 5 ou mais campos | Atendido | Nome, código, descrição, carga horária, categoria e modalidade. |
| Muitos para muitos | Atendido | Coleção `matriculas` guarda `estudante_id` e `curso_id` como `ObjectId`. |
| MongoDB Atlas | Atendido | Banco `unipar_academico` e coleções reais criados no cluster Atlas. |
| Documentos separados | Atendido | Estudantes, cursos e matrículas permanecem em coleções independentes. |
| Bootstrap | Atendido | Menu, formulários, tabelas, cartões e mensagens usam Bootstrap. |
| Persistência | Atendido | Registros foram criados, consultados, editados e removidos no Atlas. |
| Integridade | Atendido | Índices únicos para CPF, registro acadêmico, código do curso e par estudante/curso; exclusões removem matrículas relacionadas. |

## Testes executados

- Cadastro e listagem de dois estudantes com endereço completo.
- Cadastro e listagem de dois cursos.
- Matrícula de uma estudante em dois cursos e de dois estudantes no mesmo curso.
- Tentativa de matrícula duplicada bloqueada pela aplicação.
- Edição de telefone de estudante persistida no Atlas.
- Exclusão individual de matrícula.
- Exclusão de curso e de estudante com remoção das matrículas relacionadas.
- Verificação da existência das três coleções e dos documentos pelo Atlas.
- Verificação de sintaxe de todos os arquivos PHP da aplicação.
- Conferência de que `.env` e `vendor/` estão ignorados pelo Git.

