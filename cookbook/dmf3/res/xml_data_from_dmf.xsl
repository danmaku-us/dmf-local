<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="xml" indent="yes" encoding="utf-8"/>
    <xsl:template match="/">
        <information>
            <xsl:apply-templates />
        </information>
    </xsl:template>
    <!-- 弹幕内容 -->
    <xsl:template match="comment">
        <data>
            <playTime><xsl:value-of select="attr/@playtime" /></playTime>
            <message>
                <xsl:attribute name="fontsize">
                    <xsl:value-of select="attr/@fontsize" />
                </xsl:attribute>
                <xsl:attribute name="color">
                    <xsl:value-of select="attr/@color" />
                </xsl:attribute>
                <xsl:attribute name="mode">
                    <xsl:value-of select="attr/@mode" />
                </xsl:attribute>
                <xsl:value-of select="text" />
            </message>
            <times>
                <xsl:value-of select="@sendtime" />
            </times>
        </data>
    </xsl:template>
</xsl:stylesheet>