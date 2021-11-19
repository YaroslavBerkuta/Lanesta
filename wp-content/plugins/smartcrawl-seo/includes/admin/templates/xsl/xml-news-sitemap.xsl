<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:include href="templates.xsl"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <xsl:call-template name="sitemapHead"/>
            </head>
            <body>
                <xsl:call-template name="newsSitemapBody"/>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
