<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:str="http://exslt.org/strings"
                extension-element-prefixes="str">
    <xsl:output method="xml" indent="yes" encoding="utf-8"/>
    <xsl:template match="/">
        <DMFCmtPool version="0">
            <xsl:apply-templates />
        </DMFCmtPool>
    </xsl:template>
    
    <xsl:template match="data">
        <comment>
            <!-- 与弹幕本身无关的属性 -->
            <xsl:attribute name="cmtid">
                <xsl:value-of select="position()" />
            </xsl:attribute>
            <xsl:attribute name="poolid">
                <xsl:value-of select="'0'" />
            </xsl:attribute>
            <xsl:attribute name="sendtime">
                <xsl:value-of select="times/text()" />
            </xsl:attribute>
            <xsl:attribute name="user">
                <xsl:value-of select="'xml_data_to_dmf'" />
            </xsl:attribute>
            
            <!-- 必须的属性 -->
            <attr>
                <xsl:attribute name="playtime">
                    <xsl:value-of select="playTime/text()" />
                </xsl:attribute>
                <xsl:attribute name="mode">
                    <xsl:value-of select="message/@mode" />
                </xsl:attribute>
                <xsl:attribute name="fontsize">
                    <xsl:value-of select="message/@fontsize" />
                </xsl:attribute>
                <xsl:attribute name="color">
                    <xsl:value-of select="message/@color" />
                </xsl:attribute>
            </attr>
            
            <!-- 部分网站需要的其他属性 -->
            <attrext />
            
            <text>
                <xsl:value-of select="message/text()" />
            </text>
        </comment>
    </xsl:template>
    
</xsl:stylesheet>