-- Script SQL para registrar o módulo Links de Inscrição no CRUDBooster
-- Execute este SQL na sua base de dados

INSERT INTO cms_moduls (created_at, name, icon, path, table_name, controller, is_protected, is_active)
SELECT NOW(), 'Links de Inscrição', 'fa fa-link', 'links_inscricao', 'link_inscricoes', 'AdminLinksInscricaoController', 0, 1
WHERE NOT EXISTS (
    SELECT 1 FROM cms_moduls WHERE path = 'links_inscricao'
);