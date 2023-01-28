<div id="employeeQuestionsModal" class="modal fade"  
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <input type="text" name="question1" value="ما هي حركة البتكوين لليوم؟" hidden>
                <input type="text" name="question2" value="ما هي حركة مؤشر الـ Nasdaq لليوم؟" hidden>
                <input type="text" name="question_danger" value="انت مقدم على Trade خطير، فسر رغبتك في اتخاذ هذه الصفقة"
                    id="question_danger" hidden>
                <textarea name="question_danger_answer" id="question_danger_answer" hidden></textarea>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'> ما هي حركة البتكوين لليوم؟ </label>
                            <select name="answer1" id="employeeQuestion1" class="form-control" > 
                                <option selected disabled>اختر</option>
                                <option value="صاعدة">صاعدة</option>
                                <option value="هابطة">هابطة</option>
                                <option value="مستقرة">مستقرة</option>
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'> ما هي حركة مؤشر الـ Nasdaq لليوم؟ </label>
                            <select name="answer2" id="employeeQuestion2" class="form-control" > 
                                <option selected disabled>اختر</option>
                                <option value="صاعدة">صاعدة</option>
                                <option value="هابطة">هابطة</option>
                                <option value="مستقرة">مستقرة</option>
                            </select> 
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                        <fieldset class="form-group">
                            <input type="text" name="description1" class="form-control"
                                id="percentageInputQuestion1" style="visiblity:hidden; display:none">
                        </fieldset>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion2Div"  >
                        <fieldset class="form-group">
                            <input type="text" name="description2" class="form-control"
                                id="percentageInputQuestion2" style="visiblity:hidden; display:none">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' id="movingAverageQuestion"> </label>
                            <input type="hidden" name="movingAverageQuestionInput" id="movingAverageQuestionInput">
                            <select name="MAanswer" id="MAQuestion" class="form-control" > 
                                <option selected disabled>اختر</option>
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' id="rsiQuestion"> </label>
                            <input type="hidden" name="rsiQuestionInput" id="rsiQuestionInput">
                            <select name="RSIanswer" id="RSIQuestion" class="form-control" > 
                                <option selected disabled>اختر</option>
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' id="mACDQuestion"> </label>
                            <input type="hidden" name="mACDQuestionInput" id="mACDQuestionInput">
                            <select name="MACDanswer" id="MACDQuestion" class="form-control" > 
                                <option selected disabled>اختر</option>
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            </select> 
                        </fieldset>
                    </div>
                </div>

                <div class="form-group overflow-hidden" style="">
                    <div class="col-12">
                        <button type="submit" data-repeater-create="" id="createRecommendationButton" 
                            class="btn btn-primary btn-lg"> انشاء
                            <i class="icon-plus4"></i>  
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>