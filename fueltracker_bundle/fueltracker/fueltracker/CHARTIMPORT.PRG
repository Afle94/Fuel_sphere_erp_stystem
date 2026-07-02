SELECT A
USE \FUELTRACKER\DATA\DENCHART.7
COPY STRUCTURE TO DENS_CHART 
CLOSE DATABASES

CLEAR

LOCAL lcExcelFile, lcDbfFile
lcExcelFile = GETFILE("XLSX;XLS", "Select Density Chart Excel File")

IF EMPTY(lcExcelFile)
    MESSAGEBOX("Excel file not selected.", 48, "Import Cancelled")
    RETURN
ENDIF

lcDbfFile = "DENS_CHART.DBF"

USE DENS_CHART EXCLUSIVE
ZAP

LOCAL loExcel, loBook, loSheet
LOCAL lnSheet, lnRow, lnCol, lnLastRow, lnLastCol
LOCAL lnTempRow, lnTempCol, lnHeaderRow, lnDataStartRow
LOCAL lnID, lcFuelType, lnTemp, lnBaseDens, lnChartVal
LOCAL lcValue

lnID = 1

loExcel = CREATEOBJECT("Excel.Application")
loExcel.Visible = .F.

loBook = loExcel.Workbooks.Open(lcExcelFile)

FOR lnSheet = 1 TO loBook.Worksheets.Count

    loSheet = loBook.Worksheets(lnSheet)
    lcFuelType = ALLTRIM(loSheet.Name)

    lnLastRow = loSheet.UsedRange.Rows.Count + loSheet.UsedRange.Row - 1
    lnLastCol = loSheet.UsedRange.Columns.Count + loSheet.UsedRange.Column - 1

    lnTempRow = 0
    lnTempCol = 0

    FOR lnRow = 1 TO lnLastRow
        FOR lnCol = 1 TO lnLastCol
            lcValue = ALLTRIM(TRANSFORM(loSheet.Cells(lnRow, lnCol).Value))
            IF UPPER(lcValue) == "TEMP"
                lnTempRow = lnRow
                lnTempCol = lnCol
                EXIT
            ENDIF
        ENDFOR

        IF lnTempRow > 0
            EXIT
        ENDIF
    ENDFOR

    IF lnTempRow = 0
        LOOP
    ENDIF

    lnHeaderRow = lnTempRow + 1
    lnDataStartRow = lnHeaderRow + 1

    FOR lnRow = lnDataStartRow TO lnLastRow

        lcValue = ALLTRIM(TRANSFORM(loSheet.Cells(lnRow, lnTempCol).Value))

        IF EMPTY(lcValue)
            LOOP
        ENDIF

        lnTemp = VAL(lcValue)

        IF lnTemp <= 0
            LOOP
        ENDIF

        FOR lnCol = lnTempCol + 1 TO lnLastCol

            lcValue = ALLTRIM(TRANSFORM(loSheet.Cells(lnHeaderRow, lnCol).Value))

            IF EMPTY(lcValue)
                LOOP
            ENDIF

            lnBaseDens = VAL(lcValue)

            IF lnBaseDens <= 0
                LOOP
            ENDIF

            lcValue = ALLTRIM(TRANSFORM(loSheet.Cells(lnRow, lnCol).Value))

            IF EMPTY(lcValue)
                LOOP
            ENDIF

            lnChartVal = VAL(lcValue)

            IF lnChartVal <= 0
                LOOP
            ENDIF

            INSERT INTO DENS_CHART ;
                (ID, FUEL_TYPE, TEMP, BASE_DENS, CHART_VAL) ;
            VALUES ;
                (lnID, lcFuelType, lnTemp, lnBaseDens, lnChartVal)

            lnID = lnID + 1

        ENDFOR
    ENDFOR

ENDFOR

loBook.Close(.F.)
loExcel.Quit()

RELEASE loSheet, loBook, loExcel

SELECT DENS_CHART
INDEX ON FUEL_TYPE + STR(TEMP,6,2) + STR(BASE_DENS,8,2) TAG DENSKEY

MESSAGEBOX("Density Chart Imported Successfully." + CHR(13) + ;
           "Total Records Imported: " + TRANSFORM(RECCOUNT()), ;
           64, "Import Complete")

BROWSE