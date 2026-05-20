# scratch/update_erd.py
import os

def generate_drawio():
    xml = """<?xml version="1.0" encoding="UTF-8"?>
<mxfile host="app.diagrams.net" type="device">
  <diagram name="ERD" id="erd1">
    <mxGraphModel dx="1422" dy="900" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1550" pageHeight="1000" math="0" shadow="0">
      <root>
        <mxCell id="0"/>
        <mxCell id="1" parent="0"/>
        
        <!-- ==================== users ==================== -->
        <mxCell id="users" value="users" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="600" y="20" width="354" height="212" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="30" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_0_key" value="PK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_0">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_0_name" value="id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_0">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_0_type" value="INT(11) UN AI" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_0">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="users_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="56" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_1_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_1">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_1_name" value="name" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_1">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_1_type" value="VARCHAR(255)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_1">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="users_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="82" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_2">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_2_name" value="password_hash" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_2">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_2_type" value="VARCHAR(255)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_2">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="users_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="108" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_3">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_3_name" value="role" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_3">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_3_type" value="TINYINT(1)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_3">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="users_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="134" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_4">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_4_name" value="remember_token" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_4">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_4_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_4">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="users_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="160" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_5">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_5_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_5">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_5_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_5">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="users_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="users">
          <mxGeometry y="186" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="users_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_6">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_6_name" value="updated_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_6">
          <mxGeometry x="34" width="170" height="26" as="geometry"><mxRectangle width="170" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="users_row_6_type" value="DATETIME NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="users_row_6">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== students ==================== -->
        <mxCell id="students" value="students" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="600" y="320" width="354" height="264" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="30" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_0_key" value="PK, FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;fontColor=#000000;" vertex="1" parent="students_row_0">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_0_name" value="user_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_0">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_0_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_0">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="students_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="56" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_1_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_1">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_1_name" value="student_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_1">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_1_type" value="VARCHAR(50) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_1">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="students_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="82" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_2">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_2_name" value="parent_phone" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_2">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_2_type" value="VARCHAR(50) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_2">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="students_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="108" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_3">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_3_name" value="parent_email" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_3">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_3_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_3">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="students_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="134" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_4">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_4_name" value="section" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_4">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_4_type" value="VARCHAR(100) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_4">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="students_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="160" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_5">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_5_name" value="adviser_name" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_5">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_5_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_5">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="students_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="186" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_6">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_6_name" value="grade_level" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_6">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_6_type" value="VARCHAR(50) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_6">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="students_row_7" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="212" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_7_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_7">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_7_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_7">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_7_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_7">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="students_row_8" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="students">
          <mxGeometry y="238" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="students_row_8_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_8">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_8_name" value="updated_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_8">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="students_row_8_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="students_row_8">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== counselors ==================== -->
        <mxCell id="counselors" value="counselors" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="1100" y="46" width="354" height="160" as="geometry"/>
        </mxCell>
        <mxCell id="counselors_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="counselors">
          <mxGeometry y="30" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="counselors_row_0_key" value="PK, FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;fontColor=#000000;" vertex="1" parent="counselors_row_0">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_0_name" value="user_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_0">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_0_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_0">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="counselors_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="counselors">
          <mxGeometry y="56" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="counselors_row_1_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_1">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_1_name" value="counselor_email" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_1">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_1_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_1">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="counselors_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="counselors">
          <mxGeometry y="82" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="counselors_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_2">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_2_name" value="counselor_phone" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_2">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_2_type" value="VARCHAR(50) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_2">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="counselors_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="counselors">
          <mxGeometry y="108" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="counselors_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_3">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_3_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_3">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_3_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_3">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="counselors_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="counselors">
          <mxGeometry y="134" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="counselors_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_4">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_4_name" value="updated_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_4">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="counselors_row_4_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="counselors_row_4">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== admins ==================== -->
        <mxCell id="admins" value="admins" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="100" y="46" width="354" height="160" as="geometry"/>
        </mxCell>
        <mxCell id="admins_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="admins">
          <mxGeometry y="30" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="admins_row_0_key" value="PK, FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;fontColor=#000000;" vertex="1" parent="admins_row_0">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_0_name" value="user_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_0">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_0_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_0">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="admins_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="admins">
          <mxGeometry y="56" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="admins_row_1_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_1">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_1_name" value="email" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_1">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_1_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_1">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="admins_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="admins">
          <mxGeometry y="82" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="admins_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_2">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_2_name" value="phone" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_2">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_2_type" value="VARCHAR(50) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_2">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="admins_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="admins">
          <mxGeometry y="108" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="admins_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_3">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_3_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_3">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_3_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_3">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="admins_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="admins">
          <mxGeometry y="134" width="354" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="admins_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_4">
          <mxGeometry width="45" height="26" as="geometry"><mxRectangle width="45" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_4_name" value="updated_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_4">
          <mxGeometry x="45" width="159" height="26" as="geometry"><mxRectangle width="159" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="admins_row_4_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="admins_row_4">
          <mxGeometry x="204" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== vent_logs ==================== -->
        <mxCell id="vent_logs" value="vent_logs" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="1100" y="280" width="329" height="290" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="30" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_0_key" value="PK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_0">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_0_name" value="id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_0">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_0_type" value="INT(11) UN AI" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_0">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="vent_logs_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="56" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_1_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_1">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_1_name" value="user_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_1">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_1_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_1">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="82" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_2">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_2_name" value="raw_text" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_2">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_2_type" value="TEXT" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_2">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="108" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_3">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_3_name" value="emotions" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_3">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_3_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_3">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="134" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_4">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_4_name" value="risk_percent" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_4">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_4_type" value="DECIMAL(5,2) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_4">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="160" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_5">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_5_name" value="risk_level" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_5">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_5_type" value="VARCHAR(50) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_5">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="186" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_6">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_6_name" value="status" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_6">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_6_type" value="ENUM" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_6">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_7" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="212" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_7_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_7">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_7_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_7">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_7_type" value="TIMESTAMP" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_7">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_8" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="238" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_8_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_8">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_8_name" value="updated_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_8">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_8_type" value="DATETIME NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_8">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="vent_logs_row_9" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="vent_logs">
          <mxGeometry y="264" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="vent_logs_row_9_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_9">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_9_name" value="deleted_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_9">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="vent_logs_row_9_type" value="DATETIME NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="vent_logs_row_9">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== audit_logs ==================== -->
        <mxCell id="audit_logs" value="audit_logs" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="1100" y="630" width="329" height="238" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="30" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_0_key" value="PK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_0">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_0_name" value="id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_0">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_0_type" value="INT(11) UN AI" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_0">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="56" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_1_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_1">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_1_name" value="user_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_1">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_1_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_1">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="82" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_2">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_2_name" value="action" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_2">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_2_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_2">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="108" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_3">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_3_name" value="entity_type" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_3">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_3_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_3">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="134" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_4">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_4_name" value="entity_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_4">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_4_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_4">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="160" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_5">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_5_name" value="ip_address" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_5">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_5_type" value="VARCHAR(45) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_5">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="186" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_6">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_6_name" value="user_agent" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_6">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_6_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_6">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="audit_logs_row_7" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="audit_logs">
          <mxGeometry y="212" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="audit_logs_row_7_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_7">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_7_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_7">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="audit_logs_row_7_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="audit_logs_row_7">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== sms_logs ==================== -->
        <mxCell id="sms_logs" value="sms_logs" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="600" y="650" width="329" height="212" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="30" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_0_key" value="PK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_0">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_0_name" value="id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_0">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_0_type" value="INT(11) UN AI" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_0">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="sms_logs_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="56" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_1_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_1">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_1_name" value="student_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_1">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_1_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_1">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="sms_logs_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="82" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_2">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_2_name" value="parent_number" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_2">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_2_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_2">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="sms_logs_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="108" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_3">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_3_name" value="alert_type" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_3">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_3_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_3">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="sms_logs_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="134" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_4">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_4_name" value="message_content" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_4">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_4_type" value="TEXT" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_4">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="sms_logs_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="160" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_5">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_5_name" value="sent_status" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_5">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_5_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_5">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="sms_logs_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="sms_logs">
          <mxGeometry y="186" width="329" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="sms_logs_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_6">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_6_name" value="timestamp" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_6">
          <mxGeometry x="34" width="150" height="26" as="geometry"><mxRectangle width="150" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="sms_logs_row_6_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="sms_logs_row_6">
          <mxGeometry x="184" width="145" height="26" as="geometry"><mxRectangle width="145" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== bully_reports ==================== -->
        <mxCell id="bully_reports" value="bully_reports" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="100" y="280" width="414" height="316" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="30" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_0_key" value="PK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_0">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_0_name" value="id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_0">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_0_type" value="INT(11) UN AI" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_0">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="56" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_1_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_1">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_1_name" value="reporter_student_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_1">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_1_type" value="INT(11) UN NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_1">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="82" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_2">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_2_name" value="reporter_type" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_2">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_2_type" value="ENUM" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_2">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="108" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_3_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_3">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_3_name" value="victim_student_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_3">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_3_type" value="INT(11) UN NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_3">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="134" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_4_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_4">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_4_name" value="bully_student_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_4">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_4_type" value="INT(11) UN NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_4">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="160" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_5">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_5_name" value="incident_details" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_5">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_5_type" value="TEXT" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_5">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="186" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_6">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_6_name" value="evidence_file" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_6">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_6_type" value="VARCHAR(255) NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_6">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_7" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="212" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_7_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_7">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_7_name" value="status" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_7">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_7_type" value="ENUM" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_7">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_8" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="238" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_8_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_8">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_8_name" value="created_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_8">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_8_type" value="TIMESTAMP" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_8">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_9" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="264" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_9_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_9">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_9_name" value="updated_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_9">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_9_type" value="DATETIME NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_9">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="bully_reports_row_10" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="bully_reports">
          <mxGeometry y="290" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="bully_reports_row_10_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_10">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_10_name" value="deleted_at" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_10">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="bully_reports_row_10_type" value="DATETIME NULL" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="bully_reports_row_10">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- ==================== email_logs ==================== -->
        <mxCell id="email_logs" value="email_logs" style="shape=table;startSize=30;container=1;collapsible=0;childLayout=tableLayout;fixedRows=1;rowLines=0;fontStyle=1;align=center;resizeLast=1;fontSize=14;" vertex="1" parent="1">
          <mxGeometry x="100" y="650" width="414" height="264" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_0" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="30" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_0_key" value="PK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_0">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_0_name" value="id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=4;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_0">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_0_type" value="INT(11) UN AI" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_0">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        
        <mxCell id="email_logs_row_1" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="56" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_1_key" value="FK" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_1">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_1_name" value="student_id" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_1">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_1_type" value="INT(11)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_1">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="email_logs_row_2" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="82" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_2_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_2">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_2_name" value="recipient_email" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_2">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_2_type" value="VARCHAR(255)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_2">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="email_logs_row_3" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="108" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_3_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_3">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_3_name" value="alert_type" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_3">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_3_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_3">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="email_logs_row_4" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="134" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_4_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_4">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_4_name" value="subject" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_4">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_4_type" value="VARCHAR(255)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_4">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="email_logs_row_5" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="160" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_5_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_5">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_5_name" value="message_content" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_5">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_5_type" value="TEXT" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_5">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="email_logs_row_6" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="186" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_6_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_6">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_6_name" value="sent_status" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_6">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_6_type" value="VARCHAR(50)" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_6">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <mxCell id="email_logs_row_7" value="0" style="shape=tableRow;horizontal=0;startSize=0;swimlaneHead=0;swimlaneBody=0;fillColor=none;collapsible=0;dropTarget=0;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;fontSize=12;strokeColor=inherit;" vertex="1" parent="email_logs">
          <mxGeometry y="212" width="414" height="26" as="geometry"/>
        </mxCell>
        <mxCell id="email_logs_row_7_key" value="" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;fontStyle=1;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_7">
          <mxGeometry width="34" height="26" as="geometry"><mxRectangle width="34" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_7_name" value="timestamp" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_7">
          <mxGeometry x="34" width="200" height="26" as="geometry"><mxRectangle width="200" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>
        <mxCell id="email_logs_row_7_type" value="DATETIME" style="shape=partialRectangle;connectable=0;fillColor=none;top=0;left=0;bottom=0;right=0;overflow=hidden;whiteSpace=wrap;html=1;" vertex="1" parent="email_logs_row_7">
          <mxGeometry x="234" width="180" height="26" as="geometry"><mxRectangle width="180" height="26" as="alternateBounds"/></mxGeometry>
        </mxCell>

        <!-- users to subclass tables (1:1 relationships) -->
        <mxCell id="rel_users_students" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToOne;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=0;exitY=0.3;exitDx=0;exitDy=0;exitPerimeter=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="students_row_0" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="590" y="58"/>
              <mxPoint x="590" y="363"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_counselors" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToOne;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=1;exitY=0.2;exitDx=0;exitDy=0;exitPerimeter=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="counselors_row_0" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="1030" y="55"/>
              <mxPoint x="1030" y="89"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_admins" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToOne;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=0;exitY=0.15;exitDx=0;exitDy=0;exitPerimeter=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="admins_row_0" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="540" y="54"/>
              <mxPoint x="540" y="89"/>
            </Array>
          </mxGeometry>
        </mxCell>
 
        <!-- users.id to child tables -->
        <mxCell id="rel_users_vent_logs" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=1;exitY=0.4;exitDx=0;exitDy=0;exitPerimeter=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="vent_logs_row_1" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="1020" y="60"/>
              <mxPoint x="1020" y="349"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_audit_logs" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=1;exitY=0.6;exitDx=0;exitDy=0;exitPerimeter=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="audit_logs_row_1" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="1010" y="65"/>
              <mxPoint x="1010" y="699"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_sms_logs" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=1;exitY=0.8;exitDx=0;exitDy=0;exitPerimeter=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="sms_logs_row_1" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="974" y="71"/>
              <mxPoint x="974" y="719"/>
            </Array>
          </mxGeometry>
        </mxCell>
 
        <!-- users.id to bully_reports & email_logs -->
        <mxCell id="rel_users_bully_reporter" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERzeroToOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=0;exitY=0.45;exitDx=0;exitDy=0;exitPerimeter=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="bully_reports_row_1" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="550" y="62"/>
              <mxPoint x="550" y="349"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_bully_victim" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERzeroToOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=0;exitY=0.6;exitDx=0;exitDy=0;exitPerimeter=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="bully_reports_row_3" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="560" y="66"/>
              <mxPoint x="560" y="401"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_bully_bully" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERzeroToOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=0;exitY=0.75;exitDx=0;exitDy=0;exitPerimeter=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="bully_reports_row_4" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="570" y="70"/>
              <mxPoint x="570" y="427"/>
            </Array>
          </mxGeometry>
        </mxCell>
        <mxCell id="rel_users_email_logs" value="" style="edgeStyle=orthogonalEdgeStyle;rounded=0;orthogonalLoop=1;jettySize=auto;html=1;endArrow=ERzeroToMany;endFill=0;startArrow=ERmandOne;startFill=0;strokeColor=#000000;strokeWidth=2.5;exitX=0;exitY=0.9;exitDx=0;exitDy=0;exitPerimeter=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;entryPerimeter=0;" edge="1" source="users_row_0" target="email_logs_row_1" parent="1">
          <mxGeometry relative="1" as="geometry">
            <Array as="points">
              <mxPoint x="580" y="74"/>
              <mxPoint x="580" y="719"/>
            </Array>
          </mxGeometry>
        </mxCell>

      </root>
    </mxGraphModel>
  </diagram>
</mxfile>
"""
    with open('docs/erd_diagram.drawio', 'w', encoding='utf-8') as f:
        f.write(xml)
    print("Updated docs/erd_diagram.drawio")

def generate_html():
    html = """<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Shield - ERD</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Tahoma,sans-serif;background:#f5f7fa;padding:20px;color:#2d3748}
h1{text-align:center;font-size:24px;color:#1a202c;margin-bottom:6px}
.sub{text-align:center;color:#718096;font-size:14px;margin-bottom:20px}
.desc{text-align:center;color:#4a5568;font-size:13px;max-width:1000px;margin:0 auto 25px;line-height:1.6}
.canvas{position:relative;width:1550px;height:1000px;margin:0 auto;background:#fff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)}
.ent{position:absolute;border:1.5px solid #4a5568;background:#fff;font-size:12px;border-radius:6px;overflow:hidden;box-shadow:0 10px 15px -3px rgba(0, 0, 0, 0.05)}
.eh{padding:8px 12px;font-weight:700;text-align:center;border-bottom:1.5px solid #4a5568;background:#edf2f7;font-size:14px;text-transform:uppercase;letter-spacing:0.5px}
.ent table{width:100%;border-collapse:collapse}
.ent tr{border-bottom:1px solid #edf2f7}
.ent tr:last-child{border-bottom:none}
.ent td{padding:5px 10px;white-space:nowrap}
.kc{width:42px;text-align:center;font-weight:700;font-size:11px;color:#a0aec0;border-right:1px solid #edf2f7}
.kc.pk{color:#000000}
.kc.fk{color:#4a5568}
.kc.pkfk{color:#000000}
.nc{font-weight:500;padding-left:10px}
.pk-name{font-weight:700;color:#000000;text-decoration:underline}
.pkfk-name{font-weight:700;color:#000000;text-decoration:underline}
.tc{color:#4a5568;font-size:11px;text-align:right;padding-right:8px;border-left:1px solid #edf2f7}
svg.ln{position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;overflow:visible}
svg.ln polyline{fill:none}
svg.ln circle{fill:none}
.c-subclass { stroke: #000000 !important; stroke-width: 2.2px !important; }
.c-logs { stroke: #000000 !important; stroke-width: 2px !important; stroke-dasharray: 5,4 !important; }
.c-alerts { stroke: #000000 !important; stroke-width: 2px !important; stroke-dasharray: 2,3 !important; }
.c-reports { stroke: #000000 !important; stroke-width: 2.2px !important; }
.leg{position:absolute;bottom:15px;right:20px;font-size:12px;color:#000000;line-height:1.6;text-align:left;background:#ffffff;padding:12px 18px;border-radius:8px;border:2px solid #000000;box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);max-width: 320px;}
.leg b{color:#000000}
.leg .key-item { display: flex; align-items: center; margin-top: 6px; font-size: 11px; }
.leg .style-line { width: 28px; margin-right: 10px; flex-shrink: 0; }
</style>
</head>
<body>
<h1>Student Shield - Entity Relationship Diagram</h1>
<p class="sub">Updated database schema reflecting normalized profile tables (students, counselors, admins)</p>
<p class="desc">The ERD shows the updated schema layout for StudentShield. The database has been normalized by splitting specific profile details out of the main <b>users</b> table. General authentication accounts are kept in <b>users</b>, while role-specific columns reside in the 1:1 subclass tables: <b>students</b> (links advisers, parent contact data, grade, section), <b>counselors</b> (email, phone), and <b>admins</b> (email, phone). Child tables like <b>vent_logs</b>, <b>bully_reports</b>, <b>audit_logs</b>, and <b>sms_logs</b> preserve historical logging by pointing directly to the respective record ID.</p>

<div class="canvas">
<svg class="ln" style="position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:1;">
  <defs>
    <!-- Mandatory One marker (two vertical ticks) -->
    <marker id="mark-mandOne" markerWidth="12" markerHeight="10" refX="1" refY="5" orient="auto" markerUnits="userSpaceOnUse">
      <line x1="5" y1="1" x2="5" y2="9" stroke="#000000" stroke-width="1.5"/>
      <line x1="8" y1="1" x2="8" y2="9" stroke="#000000" stroke-width="1.5"/>
    </marker>
    <!-- Zero or One marker (circle + tick) -->
    <marker id="mark-zeroOne" markerWidth="12" markerHeight="10" refX="1" refY="5" orient="auto" markerUnits="userSpaceOnUse">
      <circle cx="4" cy="5" r="2.5" fill="#ffffff" stroke="#000000" stroke-width="1.5"/>
      <line x1="8" y1="1" x2="8" y2="9" stroke="#000000" stroke-width="1.5"/>
    </marker>
    <!-- Zero or Many marker (circle + crow foot) -->
    <marker id="mark-zeroMany" markerWidth="15" markerHeight="10" refX="13" refY="5" orient="auto" markerUnits="userSpaceOnUse">
      <circle cx="10" cy="5" r="2.5" fill="#ffffff" stroke="#000000" stroke-width="1.5"/>
      <line x1="1" y1="5" x2="8" y2="1" stroke="#000000" stroke-width="1.5"/>
      <line x1="1" y1="5" x2="8" y2="5" stroke="#000000" stroke-width="1.5"/>
      <line x1="1" y1="5" x2="8" y2="9" stroke="#000000" stroke-width="1.5"/>
    </marker>
  </defs>

  <!-- RELATIONSHIPS (SHARP ORTHOGONAL LINES) -->
  <!-- 1. users to admins (subclass 1:1) -->
  <path d="M 600,54 H 540 V 89 H 454" class="c-subclass" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroOne)"/>
  
  <!-- 2. users to counselors (subclass 1:1) -->
  <path d="M 954,55 H 1030 V 89 H 1100" class="c-subclass" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroOne)"/>
  
  <!-- 3. users to students (subclass 1:1) -->
  <path d="M 600,58 H 590 V 363 H 600" class="c-subclass" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroOne)"/>
  
  <!-- 4. users to vent_logs (1:N) -->
  <path d="M 954,60 H 1020 V 349 H 1100" class="c-logs" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroMany)"/>
  
  <!-- 5. users to audit_logs (1:N) -->
  <path d="M 954,65 H 1010 V 699 H 1100" class="c-logs" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroMany)"/>
  
  <!-- 6. users to sms_logs (1:N) -->
  <path d="M 954,71 H 974 V 719 H 929" class="c-alerts" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroMany)"/>
  
  <!-- 7. users to email_logs (1:N) -->
  <path d="M 600,74 H 580 V 719 H 514" class="c-alerts" fill="none" marker-start="url(#mark-mandOne)" marker-end="url(#mark-zeroMany)"/>
  
  <!-- 8. users to bully_reports (reporter 1:N) -->
  <path d="M 600,62 H 550 V 349 H 514" class="c-reports" fill="none" marker-start="url(#mark-zeroOne)" marker-end="url(#mark-zeroMany)"/>
  
  <!-- 9. users to bully_reports (victim 1:N) -->
  <path d="M 600,66 H 560 V 401 H 514" class="c-reports" fill="none" marker-start="url(#mark-zeroOne)" marker-end="url(#mark-zeroMany)"/>
  
  <!-- 10. users to bully_reports (bully 1:N) -->
  <path d="M 600,70 H 570 V 427 H 514" class="c-reports" fill="none" marker-start="url(#mark-zeroOne)" marker-end="url(#mark-zeroMany)"/>
</svg>

<!-- ENTITY TABLES -->
<!-- users -->
<div class="ent" id="e-users" style="left:600px;top:20px;width:354px;">
  <div class="eh">users</div>
  <table>
    <tr><td class="kc pk">PK</td><td class="nc pk-name">id</td><td class="tc">INT(11) UN AI</td></tr>
    <tr><td class="kc"></td><td class="nc">name</td><td class="tc">VARCHAR(255)</td></tr>
    <tr><td class="kc"></td><td class="nc">password_hash</td><td class="tc">VARCHAR(255)</td></tr>
    <tr><td class="kc"></td><td class="nc">role</td><td class="tc">TINYINT(1)</td></tr>
    <tr><td class="kc"></td><td class="nc">remember_token</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">DATETIME</td></tr>
    <tr><td class="kc"></td><td class="nc">updated_at</td><td class="tc">DATETIME NULL</td></tr>
  </table>
</div>

<!-- students -->
<div class="ent" id="e-students" style="left:600px;top:320px;width:354px;">
  <div class="eh">students</div>
  <table>
    <tr><td class="kc pkfk">PK,FK</td><td class="nc pkfk-name">user_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">student_id</td><td class="tc">VARCHAR(50) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">parent_phone</td><td class="tc">VARCHAR(50) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">parent_email</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">section</td><td class="tc">VARCHAR(100) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">adviser_name</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">grade_level</td><td class="tc">VARCHAR(50) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">DATETIME</td></tr>
    <tr><td class="kc"></td><td class="nc">updated_at</td><td class="tc">DATETIME</td></tr>
  </table>
</div>

<!-- counselors -->
<div class="ent" id="e-counselors" style="left:1100px;top:46px;width:354px;">
  <div class="eh">counselors</div>
  <table>
    <tr><td class="kc pkfk">PK,FK</td><td class="nc pkfk-name">user_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">counselor_email</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">counselor_phone</td><td class="tc">VARCHAR(50) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">DATETIME</td></tr>
    <tr><td class="kc"></td><td class="nc">updated_at</td><td class="tc">DATETIME</td></tr>
  </table>
</div>

<!-- admins -->
<div class="ent" id="e-admins" style="left:100px;top:46px;width:354px;">
  <div class="eh">admins</div>
  <table>
    <tr><td class="kc pkfk">PK,FK</td><td class="nc pkfk-name">user_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">email</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">phone</td><td class="tc">VARCHAR(50) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">DATETIME</td></tr>
    <tr><td class="kc"></td><td class="nc">updated_at</td><td class="tc">DATETIME</td></tr>
  </table>
</div>

<!-- vent_logs -->
<div class="ent" id="e-vent_logs" style="left:1100px;top:280px;width:329px;">
  <div class="eh">vent_logs</div>
  <table>
    <tr><td class="kc pk">PK</td><td class="nc pk-name">id</td><td class="tc">INT(11) UN AI</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">user_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">raw_text</td><td class="tc">TEXT</td></tr>
    <tr><td class="kc"></td><td class="nc">emotions</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">risk_percent</td><td class="tc">DECIMAL(5,2) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">risk_level</td><td class="tc">VARCHAR(50) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">status</td><td class="tc">ENUM</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">TIMESTAMP</td></tr>
    <tr><td class="kc"></td><td class="nc">updated_at</td><td class="tc">DATETIME NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">deleted_at</td><td class="tc">DATETIME NULL</td></tr>
  </table>
</div>

<!-- audit_logs -->
<div class="ent" id="e-audit_logs" style="left:1100px;top:630px;width:329px;">
  <div class="eh">audit_logs</div>
  <table>
    <tr><td class="kc pk">PK</td><td class="nc pk-name">id</td><td class="tc">INT(11) UN AI</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">user_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">action</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">entity_type</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">entity_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">ip_address</td><td class="tc">VARCHAR(45) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">user_agent</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">DATETIME</td></tr>
  </table>
</div>

<!-- sms_logs -->
<div class="ent" id="e-sms_logs" style="left:600px;top:650px;width:329px;">
  <div class="eh">sms_logs</div>
  <table>
    <tr><td class="kc pk">PK</td><td class="nc pk-name">id</td><td class="tc">INT(11) UN AI</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">student_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">parent_number</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">alert_type</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">message_content</td><td class="tc">TEXT</td></tr>
    <tr><td class="kc"></td><td class="nc">sent_status</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">timestamp</td><td class="tc">DATETIME</td></tr>
  </table>
</div>

<!-- bully_reports -->
<div class="ent" id="e-bully_reports" style="left:100px;top:280px;width:414px;">
  <div class="eh">bully_reports</div>
  <table>
    <tr><td class="kc pk">PK</td><td class="nc pk-name">id</td><td class="tc">INT(11) UN AI</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">reporter_student_id</td><td class="tc">INT(11) UN NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">reporter_type</td><td class="tc">ENUM</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">victim_student_id</td><td class="tc">INT(11) UN NULL</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">bully_student_id</td><td class="tc">INT(11) UN NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">incident_details</td><td class="tc">TEXT</td></tr>
    <tr><td class="kc"></td><td class="nc">evidence_file</td><td class="tc">VARCHAR(255) NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">status</td><td class="tc">ENUM</td></tr>
    <tr><td class="kc"></td><td class="nc">created_at</td><td class="tc">TIMESTAMP</td></tr>
    <tr><td class="kc"></td><td class="nc">updated_at</td><td class="tc">DATETIME NULL</td></tr>
    <tr><td class="kc"></td><td class="nc">deleted_at</td><td class="tc">DATETIME NULL</td></tr>
  </table>
</div>

<!-- email_logs -->
<div class="ent" id="e-email_logs" style="left:100px;top:650px;width:414px;">
  <div class="eh">email_logs</div>
  <table>
    <tr><td class="kc pk">PK</td><td class="nc pk-name">id</td><td class="tc">INT(11) UN AI</td></tr>
    <tr><td class="kc fk">FK</td><td class="nc">student_id</td><td class="tc">INT(11)</td></tr>
    <tr><td class="kc"></td><td class="nc">recipient_email</td><td class="tc">VARCHAR(255)</td></tr>
    <tr><td class="kc"></td><td class="nc">alert_type</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">subject</td><td class="tc">VARCHAR(255)</td></tr>
    <tr><td class="kc"></td><td class="nc">message_content</td><td class="tc">TEXT</td></tr>
    <tr><td class="kc"></td><td class="nc">sent_status</td><td class="tc">VARCHAR(50)</td></tr>
    <tr><td class="kc"></td><td class="nc">timestamp</td><td class="tc">DATETIME</td></tr>
  </table>
</div>

<div class="leg">
  <b>PK</b> = Primary Key &nbsp; <b>FK</b> = Foreign Key<br>
  <b>UN</b> = Unsigned &nbsp; <b>AI</b> = Auto Increment<br>
</div>
</div>
</body>
</html>
"""
    with open('docs/erd_diagram.html', 'w', encoding='utf-8') as f:
        f.write(html)
    print("Updated docs/erd_diagram.html")

if __name__ == '__main__':
    generate_drawio()
    generate_html()
