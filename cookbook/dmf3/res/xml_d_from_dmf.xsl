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
            <!-- 不知道怎么回事合并到一起查的话会有顺序错误 -->
            <!-- p段格式：播放时间,模式,字体大小，颜色，日期，弹幕池，userid，弹幕id -->
            <xsl:attribute name="p">
                <xsl:for-each select="playtime | mode | fontsize | color">
                    <xsl:value-of select="." />
                    <xsl:if test="position() != last()">,</xsl:if>
                </xsl:for-each>
                <xsl:text>,</xsl:text>
                <xsl:for-each select="@sendtime | @poolid | @userhash | @cmtid">
                    <xsl:value-of select="." />
                    <xsl:if test="position() != last()">,</xsl:if>
                </xsl:for-each>
            </xsl:attribute>
            <xsl:value-of select="text" />
        </d>
    </xsl:template>
</xsl:stylesheet>