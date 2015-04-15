# yaml-articles

wordpress plugin for listing articles from a YAML file  
developed for <http://medialabufrj.net>

### Usage

Upload .yml file and use the shortcodes:

    [list_articles file="http://example.com/path/to/dataset.yml"]
    
    # or, using filters
    
    [list_articles file="http://example.com/path/to/dataset.yml" filter="published"]


### YAML model

```yaml
basepath: "http://example.com/path/to/pdf/files/"

summary: "Seções / Sections / Secciones"
themes:
  # 1st theme, with multilingual titles
  - title:
      - Apresentação (BR)
      - Presentacíon (ES)
      - Presentation (EN)
  # 2nd theme, with only one language
  - title:
      - Title only in English
      
articles:

  # 1st article
  - theme: 0
    title:
      - "Pela formação de uma rede Latino-Americana de estudos sobre vigilância"
      - "Por la formación de una red Latino-Americana de estudios sobre vigilancia"
      - "For the creation of a Latin-American network of surveillance studies"
    page: "p. 1-5"
    file: "file1.pdf"
    authors: "Rodrigo Firmino, Fernanda Bruno e Marta Kanashiro"
    filter: published
    
  # 2nd article
  - theme: 1
    title:
      - "Padrões de criminalidade e espaço público: o centro do Rio de Janeiro"
    page: "p. 6-10"
    file: "file2.pdf"
    authors: "David Morais"
    filter: not-published
```
