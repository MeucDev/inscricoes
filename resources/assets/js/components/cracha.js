import jsPDF from 'jspdf';
import swal from 'sweetalert2';
const imgLar = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAywDLAAD/4QkgaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJYTVAgQ29yZSA1LjUuMCI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiLz4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8P3hwYWNrZXQgZW5kPSJ3Ij8+/+0ALFBob3Rvc2hvcCAzLjAAOEJJTQQlAAAAAAAQ1B2M2Y8AsgTpgAmY7PhCfv/iAmRJQ0NfUFJPRklMRQABAQAAAlRsY21zBDAAAG1udHJSR0IgWFlaIAfiAAMAFwAWACoAM2Fjc3BNU0ZUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD21gABAAAAANMtbGNtcwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC2Rlc2MAAAEIAAAAPmNwcnQAAAFIAAAATHd0cHQAAAGUAAAAFGNoYWQAAAGoAAAALHJYWVoAAAHUAAAAFGJYWVoAAAHoAAAAFGdYWVoAAAH8AAAAFHJUUkMAAAIQAAAAIGdUUkMAAAIQAAAAIGJUUkMAAAIQAAAAIGNocm0AAAIwAAAAJG1sdWMAAAAAAAAAAQAA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';
const imgQuiosque = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAywDLAAD/4QkgaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJYTVAgQ29yZSA1LjUuMCI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiLz4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8P3hwYWNrZXQgZW5kPSJ3Ij8+/+0ALFBob3Rvc2hvcCAzLjAAOEJJTQQlAAAAAAAQ1B2M2Y8AsgTpgAmY7PhCfv/iAmRJQ0NfUFJPRklMRQABAQAAAlRsY21zBDAAAG1udHJSR0IgWFlaIAfiAAMAFwAWACoAM2Fjc3BNU0ZUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD21gABAAAAANMtbGNtcwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAC2Rlc2MAAAEIAAAAPmNwcnQAAAFIAAAATHd0cHQAAAGUAAAAFGNoYWQAAAGoAAAALHJYWVoAAAHUAAAAFGJYWVoAAAHoAAAAFGdYWVoAAAH8AAAAFHJUUkMAAAIQAAAAIGdUUkMAAAIQAAAAIGJUUkMAAAIQAAAAIGNocm0AAAIwAAAAJG1sdWMAAAAAAAAAAQAAAAxlblVTAAAAIgAAABwAcwBSAEcAQgAgAEkARQBDADYAMQA5ADYANgAtADIALgAxAABtbHVjAAAAAAAAAAEAAAAMZW5VUwAAADAAAAAcAE4AbwAgAGMAbwBwAHkAcgBpAGcAaAB0ACwAIAB1AHMAZQAgAGYAcgBlAGUAbAB5WFlaIAAAAAAAAPbWAAEAAAAA0y1zZjMyAAAAAAABDEIAAAXe///zJQAAB5MAAP2Q///7of///aIAAAPcAADAblhZWiAAAAAAAABvoAAAOPUAAAOQWFlaIAAAAAAAACSfAAAPhAAAtsNYWVogAAAAAAAAYpcAALeHAAAY2XBhcmEAAAAAAAMAAAACZmYAAPKnAAANWQAAE9AAAApbY2hybQAAAAAAAwAAAACj1wAAVHsAAEzNAACZmgAAJmYAAA9c/9sAQwABAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB/9sAQwEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB/8AAEQgAOAA4AwERAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A/wA/+gAoAKACgAoAKACgAoAKAPV/gZ8Dvit+0r8Xfh/8B/gd4K1n4ifFf4o+JLHwr4J8H6FCst9q2rXpZi0ksrxWmnaXp1pFc6prmt6lPaaRoOi2Woa1rF7ZaXYXd3CAf6M//EIN+zr/AMOvP+FCf2/pH/Dw7/kqX/DT+dS/4Rv/AIWj/ZHk/wDCmfsPk/a/+FAeT/xT323+zP8AhKBrf/F0PsP2j/ihqAP85j45/A/4rfs1/F34gfAj44eCtZ+HfxX+F/iS+8K+NvB+vQrFf6Tq1iVYNHLE8tpqOl6jaSW2qaHrem3F3pGvaLe6frWj3t7pd/aXcwB5RQAUAFABQB/cT/waO/Gj9hP4bfFeL4c+B/2av2uvjx/wUK+L1pqtv48+M2m/DT4Nr8Cf2bPghYahAt6bDxjrXx2sfE+i+Fb1xpWoePPF8/gu18T+LNdutA+H/hTwvqdzbabbeKQD/RvoA/zj/wDg7i+NH7CfxJ+K83w58b/s1ftdfAf/AIKFfCK00q38B/GbU/hp8Gz8Cf2k/ghfajOlmb/xjovx2vvE+teFbJjquoeA/F8Hgu68T+FNctdf+H/ivwxplzc6jbeFgD+HegAoAKANHR7K11LVtL06+1ew8P2V/qNlZXmvarFqtxpeiWt1cxQXGr6lBoWm6zrc1hpsUj3l5Fo+karqslvDImn6bfXbQ2soB/q+/wDBtDb/APBLbwL+zh4s+D//AATt8YeMfjp478PQeG9b/au/aW8QfAj4qfDW1+IHxGvYJF0zQoPE3j/wjoemW2j6HBcakfAfwu0bVtSuvDfhx7zxBrCXWueIdb8Ra8Af01UAfzK/8HL1v/wS18dfs4eE/g//AMFEvGHjH4F+O/EMHiTW/wBlH9pbw/8AAj4qfEq1+H/xGsoIl1PQp/E3gDwjrml3Oj65Bb6afHnwu1nVtNuvEnhxbPxBo6WuueH9E8RaEAf5QWsWVrpurapp1jq9h4gsrDUb2ys9e0qLVbfS9btbW5lgt9X02DXdN0bW4bDUoo0vLOLWNI0rVY7eaNNQ02xu1mtYgDOoAKAPpP8AZG+D3wq+O3x/8AfDr45fH/wb+zD8H9T1MXXxH+NPjay1nV7Pwl4TsCs+qnQ/D/h/TtT1TxH4u1KH/iXeF9FjggsbjVrmCbWtS0nRLbUdTtAD/Uy/Yo/4Krf8G9n7GXwS+F37Jn7LH7YHwk8MeCPDP2LQ9D06Lw78TJ/EXjLxbrU9vbah4s8Ya23w8tW8QeNvF+qvHda1rV4II3meCysodN0Ww07TrIA/oT8W+KvD3gTwr4m8b+LdTh0Twr4N8P614q8TazcpNJb6R4e8Pabc6vrWp3EdtFPcPDYabZ3N1KlvDNM0cTCKKRyqEA/nb/bB/wCCtf8Awbuft1/s++Pf2Z/2kP2yfhD40+Gfj/TzDcR/8I58ULbXvDGu2yyNoXjbwXrLfDmebw74y8MXkn2/Q9Zt45AkgmsdQttQ0e+1LTL0A/y3v2uPg98K/gV8fvH/AMOvgd8f/Bv7T3wf0zUzdfDj40+CbLWdIsvFvhO/LT6Udb8P+INO0zVPDni7Tof+Jd4o0WSCeyt9Wtp5tG1LV9EudN1S7APmygAoAKAPRfhBe2em/Fr4XajqN3bWGn2HxF8E3t/f3s8VrZ2Vna+JdMnuru7up3jgtra2gjeaeeZ0ihiR5JHVFJAB/sZftff8FPv+CaniT9kz9qHw74d/4KG/sNa/4g1/9nb42aLoWhaL+1p8A9V1nWtZ1X4a+JrHTNJ0nTLHx/Pe6jqeo3s8FnYWFnBNdXl1NFb28Uk0iIQD/GIoAKACgAoAKACgAoAKACgAoA//2Q==';


// define a mixin object
export default {
    methods: {
        imprimirEquipe(equipe) {
            var doc = new jsPDF({
                unit: 'mm',
                orientation: 'landscape',
                // https://github.com/parallax/jsPDF/blob/ddbfc0f0250ca908f8061a72fa057116b7613e78/jspdf.js#L791
                // 72 / 25,4 * 84 = 238
                // 72 / 25,4 * 50 = 141
                format: [238, 141]
            });

            doc.setProperties({
                title: "Crachas - " + equipe.nome
            });

            var self = this;
            equipe.membros.forEach(function(item, i) {
                if (item.imprimir){
                    if(i > 0)
                        doc.addPage();
                    item.evento = equipe.evento;
                    item.equipe = equipe.nome;
                    self.generatePdfEquipe(doc, item);
                }
            });

            var pdf64 = btoa(doc.output());
            var blob = this.b64toBlob(pdf64, 'application/pdf');
            var blobUrl = URL.createObjectURL(blob);

            var w = window.open(blobUrl);

            if (!w){
                swal('Oops...', 'Para a impressão dos crachas, você deve aceitar abrir popup no browser!', 'warning');
            }else{
                w.print();
            }
        },
        generatePdfEquipe(doc, membroEquipe) {
            var membro = {
                event: membroEquipe.evento,
                city: membroEquipe.equipe,
                nickname: membroEquipe.apelido,
                fullname: membroEquipe.nome
            };

            this.generatePdf64(doc, membro);
        },
        imprimir : function (inscricao){
            var doc = new jsPDF({
                unit: 'mm',
                orientation: 'landscape',
                // https://github.com/parallax/jsPDF/blob/ddbfc0f0250ca908f8061a72fa057116b7613e78/jspdf.js#L791
                // 72 / 25,4 * 84 = 238
                // 72 / 25,4 * 50 = 141
                format: [238, 141]
            });

            doc.setProperties({
                title: "Crachas - " + inscricao.pessoa.nome
            });

            this.generatePdfInscricao(doc, inscricao);

            var self = this;
            inscricao.dependentes.forEach(function(item) {
                if (item.imprimir){
                    doc.addPage();
                    self.generatePdfInscricao(doc, item);
                }
            });

            var pdf64 = btoa(doc.output());
            var blob = this.b64toBlob(pdf64, 'application/pdf');
            var blobUrl = URL.createObjectURL(blob);

            var w = window.open(blobUrl);

            if (!w){
                swal('Oops...', 'Para a impressão dos crachas, você deve aceitar abrir popup no browser!', 'warning');
            }else{
                w.print();
            }
        },
        generatePdfInscricao(doc, inscricao){
            var pessoa = {
                event: this.inscricao.evento.nome,
                city: this.inscricao.pessoa.cidade + " - " + this.inscricao.pessoa.uf,
                nickname: inscricao.pessoa.nomecracha,
                fullname: inscricao.pessoa.nome
            };

            if (this.inscricao.equipeRefeicao){
                pessoa.eatPlace = (this.inscricao.refeicao.startsWith("LAR"))? 'L': 'Q';
                pessoa.eatGroup = (this.inscricao.equipeRefeicao.endsWith("A"))? 'A': 'B';
            }

            this.generatePdf64(doc, pessoa);
        },
        generatePdf64 : function (doc, data) {
            // - - - - Generate PDF

            doc.setDrawColor(0, 0, 0);

            // Event
            doc.setFontSize(12);
            doc.setFont('helvetica');
            doc.text(4, 7.5, data.event);

            if (data.eatPlace){
                // Image
                doc.addImage(data.eatPlace == 'Q' ? imgQuiosque : imgLar, 'PNG', 67, 2.5, 7, 7);

                // Square
                doc.setLineWidth(0.5);
                doc.rect(74.25, 2.75, 6.5, 6.5);

                // Group
                doc.setFont('helvetica');
                doc.text(77.5, 7.5, data.eatGroup, 'center');
            }

            // Line
            doc.setLineWidth(0.5);
            doc.line(0, 12, 84, 12);

            // Nickname
            doc.setFontType('bold');
            doc.setFontSize(22);
            doc.text(data.nickname.toUpperCase(), 42, 27, 'center');

            // Full name
            doc.setFontType('normal');
            doc.setFontSize(11);
            var text = doc.splitTextToSize(data.fullname, 60);
            doc.text(text, 42, 32, 'center');

            // City
            doc.setFontSize(9);
            doc.text(data.city, 42, 42, 'center');
        },
        b64toBlob: function b64toBlob(b64Data, contentType, sliceSize) {
            contentType = contentType || '';
            sliceSize = sliceSize || 512;
        
            var byteCharacters = atob(b64Data);
            var byteArrays = [];
        
            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                var slice = byteCharacters.slice(offset, offset + sliceSize);
        
                var byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
        
                var byteArray = new Uint8Array(byteNumbers);
        
                byteArrays.push(byteArray);
            }
        
            var blob = new Blob(byteArrays, {
                type: contentType
            });
            return blob;
        }        
    }
}

