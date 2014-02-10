<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:str="http://exslt.org/strings"
                extension-element-prefixes="str">
    <xsl:output method="xml" indent="yes" encoding="utf-8"/>
    <xsl:template match="/">
        <DMFCmtPool version="0">
            <xsl:for-each select="/i/d">
                <xsl:call-template name="d">
                    <xsl:with-param name="params" select="str:split(@p, ',')" />
                    <xsl:with-param name="text"   select="text()" />
                </xsl:call-template>
            </xsl:for-each>
        </DMFCmtPool>
    </xsl:template>

    <!-- 弹幕内容 -->
    <xsl:template name="d">
        <xsl:param name="params"/>
        <xsl:param name="text"/>
        <!-- p段格式：播放时间1,模式2,字体大小3，颜色4，发送时间5，弹幕池6，userhash7，弹幕id8 -->
        <!-- 从1开始                                                                      -->
        <!-- example : 200.75700378418,1,25,10027212,1375173703,0,a78782cd,271189381     -->
        <comment>
            <xsl:attribute name="cmtid">
                <xsl:value-of select="$params[8]" />
            </xsl:attribute>
            <xsl:attribute name="poolid">
                <xsl:value-of select="$params[6]" />
            </xsl:attribute>
            <xsl:attribute name="sendtime">
                <xsl:value-of select="$params[5]" />
            </xsl:attribute>
            <xsl:attribute name="user">
                <xsl:value-of select="$params[7]" />
            </xsl:attribute>
            
            
            <playtime>
                <xsl:value-of select="$params[1]" />
            </playtime>
            <mode>
                <xsl:value-of select="$params[2]" />
            </mode>
            <fontsize>
                <xsl:value-of select="$params[3]" />
            </fontsize>
            <color>
                <xsl:value-of select="$params[4]" />
            </color>
            <attr/>
            <text>
                <xsl:value-of select="text()" />
            </text>
        </comment>
    </xsl:template>
    
</xsl:stylesheet>