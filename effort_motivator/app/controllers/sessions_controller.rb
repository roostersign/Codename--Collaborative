class SessionsController < ApplicationController

  def new
  end

  def create
    if params[:session][:type] == 1
      student = Student.find_by_name(params[:session][:name])
      if student && student.authenticate(params[:session][:password])
        sign_in student
	redirect_to student
	# Sign the user in and redirect to the user's show page.
      else
        flash.now[:error] = 'Invalid email/password combination'
        render 'new'
      end
    else
      teacher = Teacher.find_by_name(params[:session][:name])
      if teacher && teacher.authenticate(params[:session][:password])
        sign_in teacher
	redirect_to teacher
      else
        flash.now[:error] = 'Invalid email/password combination'
        render 'new'
      end
    end
  end

  def destroy
    sign_out
    redirect_to root_path
  end

end
