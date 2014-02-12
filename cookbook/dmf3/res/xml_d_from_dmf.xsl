<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="xml" indent="yes" encoding="utf-8"/>
    <xsl:template match="/">
        <i>
            <!-- 主体 -->
            <chatserver />
            <chatid>0</chatid>
            <mission>0</mission>
            <source>0</source>
            <xsl:apply-templates />
        </i>
    </xsl:template>
    <!-- 弹幕内容 -->
    <xsl:template match="comment">
        <d>
            <!-- p段格式：播放时间,模式,字体大小，颜色，日期，弹幕池，userid，弹幕id -->
            <xsl:attribute name="p">
                <!-- XSLT 1.0 没有seq类型，没找到更简单的方式就无脑流了-->
                <xsl:value-of select="attr/@playtime" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="attr/@mode" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="attr/@fontsize" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="attr/@color" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="@sendtime" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="@poolid" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="@user" />
                <xsl:text>,</xsl:text>
                <xsl:value-of select="@cmtid" />
            </xsl:attribute>
            <xsl:value-of select="text" />
        </d>
    </xsl:template>
</xsl:stylesheet>